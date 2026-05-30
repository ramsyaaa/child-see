<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OtpCode;
use App\Mail\OtpVerificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OtpController extends Controller
{
    /**
     * Get the appropriate dashboard route based on user role.
     */
    private function getDashboardRoute(User $user)
    {
        switch($user->role) {
            case 'ADMIN':
                return route('admin.dashboard');
            case 'BIDDER':
                return route('user.dashboard');
            default:
                return '/';
        }
    }

    /**
     * Show OTP verification page.
     */
    public function showVerifyPage()
    {
        // Check if user is authenticated and has OTP status
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // If user is not in OTP status, redirect to dashboard
        if (!$user->isOtp()) {
            return redirect()->to($this->getDashboardRoute($user))->with('info', 'Email Anda sudah terverifikasi.');
        }

        // Get the latest OTP code for this user
        $otpCode = $user->latestOtpCode()->first();

        // If no OTP code exists, create one
        if (!$otpCode) {
            $this->sendOtp($user);
            $otpCode = $user->latestOtpCode()->first();
        }

        return view('auth.verify-otp', [
            'user' => $user,
            'otpCode' => $otpCode,
        ]);
    }

    /**
     * Verify OTP code.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6|regex:/^[0-9]{6}$/',
        ], [
            'otp_code.required' => 'Kode OTP wajib diisi.',
            'otp_code.size' => 'Kode OTP harus terdiri dari 6 digit.',
            'otp_code.regex' => 'Kode OTP hanya boleh berisi angka.',
        ]);

        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Check if user is in OTP status
        if (!$user->isOtp()) {
            return redirect()->to($this->getDashboardRoute($user))->with('info', 'Email Anda sudah terverifikasi.');
        }

        // Get the latest OTP code
        $otpCode = $user->latestOtpCode()->first();

        if (!$otpCode) {
            return back()->with('error', 'Kode OTP tidak ditemukan. Silakan minta kode OTP baru.');
        }

        // Check if OTP is expired
        if ($otpCode->isExpired()) {
            return back()->with('error', 'Kode OTP telah kadaluarsa. Silakan minta kode OTP baru.');
        }

        // Check if OTP code matches
        if ($otpCode->otp_code !== $request->otp_code) {
            $otpCode->incrementAttempts();

            // Check if maximum attempts exceeded
            if ($otpCode->hasExceededVerificationAttempts()) {
                return back()->with('error', 'Anda telah melampaui batas percobaan. Silakan minta kode OTP baru.');
            }

            $remainingAttempts = 5 - $otpCode->otp_attempts;
            return back()->with('error', "Kode OTP salah. Anda memiliki {$remainingAttempts} percobaan lagi.");
        }

        // OTP is correct, update user status to PENDING
        $user->update(['status' => 'PENDING']);

        // Delete the OTP code
        $otpCode->delete();

        Log::info('User email verified via OTP', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        // Redirect to appropriate dashboard based on user role
        return redirect()->to($this->getDashboardRoute($user))->with('success', 'Email Anda berhasil diverifikasi! Silakan lengkapi data diri Anda untuk melanjutkan.');
    }

    /**
     * Resend OTP code.
     */
    public function resendOtp(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu.',
            ], 401);
        }

        $user = Auth::user();

        // Check if user is in OTP status
        if (!$user->isOtp()) {
            return response()->json([
                'success' => false,
                'message' => 'Email Anda sudah terverifikasi.',
            ], 400);
        }

        // Get the latest OTP code
        $otpCode = $user->latestOtpCode()->first();

        if (!$otpCode) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP tidak ditemukan.',
            ], 404);
        }

        // Check if user can resend (cooldown period)
        if (!$otpCode->canResend()) {
            $cooldownSeconds = $otpCode->getResendCooldownSeconds();
            return response()->json([
                'success' => false,
                'message' => "Silakan tunggu {$cooldownSeconds} detik sebelum meminta kode OTP baru.",
                'cooldown_seconds' => $cooldownSeconds,
            ], 429);
        }

        // Check if resend counter should be reset
        if ($otpCode->shouldResetResendCounter()) {
            $otpCode->update(['otp_resend_count' => 0]);
            $otpCode->refresh(); // Refresh the model to get updated data
        }

        // Check if user has exceeded maximum resend attempts
        if ($otpCode->hasExceededResendAttempts()) {
            $resetSeconds = $otpCode->getResendResetSeconds();
            return response()->json([
                'success' => false,
                'message' => "Anda telah mencapai batas permintaan OTP. Silakan coba lagi dalam {$resetSeconds} detik.",
                'reset_seconds' => $resetSeconds,
            ], 429);
        }

        // Generate new OTP code
        $newOtpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Log::debug('New OTP code generated for resend', [
            'user_id' => $user->id,
            'new_otp_code' => $newOtpCode,
        ]);

        // Update OTP code
        Log::info('Updating OTP code for resend', [
            'user_id' => $user->id,
            'old_resend_count' => $otpCode->otp_resend_count,
            'new_resend_count' => $otpCode->otp_resend_count + 1,
        ]);

        $otpCode->update([
            'otp_code' => $newOtpCode,
            'otp_expires_at' => now()->addMinutes(10),
            'otp_attempts' => 0,
            'otp_last_sent_at' => now(),
            'otp_resend_count' => $otpCode->otp_resend_count + 1,
        ]);

        // Refresh the model to ensure datetime fields are properly cast
        $otpCode->refresh();
        Log::debug('OTP code refreshed after update', [
            'user_id' => $user->id,
            'otp_last_sent_at' => $otpCode->otp_last_sent_at,
        ]);

        // Send OTP email
        try {
            Log::info('Attempting to resend OTP email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'otp_code' => $newOtpCode,
                'resend_count' => $otpCode->otp_resend_count,
            ]);

            Mail::to($user->email)->send(new OtpVerificationMail($user, $otpCode));

            Log::info('OTP resent to user successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'resend_count' => $otpCode->otp_resend_count,
                'timestamp' => now()->toDateTimeString(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kode OTP baru telah dikirim ke email Anda.',
                'remaining_attempts' => $otpCode->getRemainingResendAttempts(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to resend OTP', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            Log::debug('Resend OTP exception trace', [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim kode OTP. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Send OTP code to user email.
     */
    public function sendOtp(User $user)
    {
        try {
            // Log: Start of OTP sending process
            Log::info('Starting OTP send process', [
                'user_id' => $user->id,
                'email' => $user->email,
                'timestamp' => now()->toDateTimeString(),
            ]);

            // Generate 6-digit OTP code
            $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            Log::debug('OTP code generated', [
                'user_id' => $user->id,
                'otp_code' => $otpCode,
            ]);

            // Create or update OTP code record
            $otp = OtpCode::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'otp_code' => $otpCode,
                    'otp_expires_at' => now()->addMinutes(10),
                    'otp_attempts' => 0,
                    'otp_last_sent_at' => now(),
                    'otp_first_sent_at' => now(),
                    'otp_resend_count' => 0,
                ]
            );

            Log::debug('OTP record created/updated', [
                'user_id' => $user->id,
                'otp_id' => $otp->id,
                'expires_at' => $otp->otp_expires_at,
            ]);

            // Log: SMTP configuration check
            Log::debug('SMTP Configuration', [
                'mailer' => config('mail.mailer'),
                'host' => config('mail.host'),
                'port' => config('mail.port'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
            ]);

            // Send OTP email
            Log::info('Attempting to send OTP email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'otp_code' => $otpCode,
            ]);

            Mail::to($user->email)->send(new OtpVerificationMail($user, $otp));

            Log::info('OTP email sent successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'otp_code' => $otpCode,
                'timestamp' => now()->toDateTimeString(),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send OTP', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            Log::debug('Exception trace', [
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }
}
