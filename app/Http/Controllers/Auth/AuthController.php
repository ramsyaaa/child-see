<?php

namespace App\Http\Controllers\Auth;

use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function loginpage() {
        return view('auth.login');
    }

    public function unifiedAuthPage() {
        return view('auth.login');
    }

    public function loginUser(Request $req)
    {
        $req->validate([
            'phone_number' => 'required|string',
            'password' => 'required',
        ], [
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $inputPhone = $req->get('phone_number');
        $password = $req->get('password');

        // Normalize phone number for lookup
        $normalizedPhone = $this->normalizeIndonesianPhone($inputPhone);

        // Try to find user with normalized phone number first
        $user = User::where('phone_number', $normalizedPhone)->first();

        // If not found, try with original input (for backward compatibility)
        if (!$user) {
            $user = User::where('phone_number', $inputPhone)->first();
        }

        // Check if user exists and account is active
        if (!$user) {
            return redirect()->back()->withInput($req->only('phone_number'))->with('error', 'Nomor telepon tidak ditemukan dalam sistem');
        }

        if ($user->status === 'SUSPENDED') {
            return redirect()->back()->withInput($req->only('phone_number'))->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.');
        }

        if ($user->status === 'REJECTED') {
            return redirect()->back()->withInput($req->only('phone_number'))->with('error', 'Akun Anda telah ditolak. Silakan hubungi administrator untuk informasi lebih lanjut.');
        }

        if ($user->status === 'REJECTED') {
            $message = 'Akun Anda ditolak oleh admin.';
            if ($user->rejection_reason) {
                $message .= ' Alasan: ' . $user->rejection_reason;
            }
            return redirect()->back()->withInput($req->only('phone_number'))->with('error', $message);
        }

        if (\Hash::check($password, $user->password_hash)) {
            auth()->guard('web')->login($user);
            session(["phone_number" => $user->phone_number]);
            session(['last_activity' => time()]); // Set initial session activity timestamp

            // Check if user is in OTP status and redirect to OTP verification
            if ($user->isOtp()) {
                return redirect()->route('otp.verify')->with('info', 'Silakan verifikasi email Anda terlebih dahulu untuk melanjutkan.');
            }

            // Role-based redirect for auction system
            switch($user->role) {
                case 'ADMIN':
                    return redirect('/admin')->with('success', 'Selamat datang di panel admin!');
                case 'BIDDER':
                    return redirect('/dashboard')->with('success', 'Berhasil masuk ke akun Anda. Selamat datang!');
                default:
                    return redirect('/')->with('success', 'Berhasil masuk ke akun Anda');
            }
        } else {
            return redirect()->back()->withInput($req->only('phone_number'))->with('error', 'Kata sandi yang Anda masukkan salah');
        }
    }

    public function registerPage() {
        return view('auth.register');
    }

    public function registerUser(Request $req)
    {
        $req->validate([
            'full_name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:50',
            'password' => 'required|min:6|confirmed',
            'nik' => 'required|string|size:16|unique:users,nik|regex:/^[0-9]+$/',
            'ktp_photo' => 'required|image|mimes:jpeg,jpg,png|max:2048', // Max 2MB
            'npwp' => 'nullable|string|size:15|regex:/^[0-9.-]+$/',
        ], [
            'nik.required' => 'NIK (National ID Number) is required.',
            'nik.size' => 'NIK must be exactly 16 digits.',
            'nik.unique' => 'This NIK is already registered.',
            'nik.regex' => 'NIK must contain only numbers.',
            'ktp_photo.required' => 'KTP/ID Card photo is required.',
            'ktp_photo.image' => 'KTP photo must be an image file.',
            'ktp_photo.mimes' => 'KTP photo must be in JPG or PNG format.',
            'ktp_photo.max' => 'KTP photo size must not exceed 2MB.',
            'npwp.size' => 'NPWP must be exactly 15 characters.',
            'npwp.regex' => 'NPWP format is invalid.',
        ]);

        // Handle KTP photo upload
        $ktpPhotoPath = null;
        if ($req->hasFile('ktp_photo')) {
            $ktpPhotoPath = $req->file('ktp_photo')->store('ktp_photos', 'public');
        }

        $user = User::create([
            'full_name' => $req->full_name,
            'email' => $req->email,
            'phone_number' => $req->phone_number,
            'password_hash' => \Hash::make($req->password),
            'role' => 'BIDDER', // Default role for new registrations
            'status' => 'ACTIVE',
            'nik' => $req->nik,
            'ktp_photo' => $ktpPhotoPath,
            'npwp' => $req->npwp,
        ]);

        alert()->success('Registration successful! You can now login.');
        return redirect()->route('login');
    }

    public function bidderRegisterPage() {
        return view('auth.register');
    }

    public function registerBidder(Request $req)
    {
        try {
            // Sanitize phone number - remove spaces and special characters
            $phoneNumber = preg_replace('/[^0-9+]/', '', $req->input('phone_number', ''));

            $req->merge(['phone_number' => $phoneNumber]);

            // Validate phone number format using custom validation
            $phoneRegex = '/^(\+62|62|0)8[1-9][0-9]{7,10}$/';
            if (!preg_match($phoneRegex, $phoneNumber)) {
                return redirect()->back()
                    ->withErrors(['phone_number' => 'Format nomor telepon tidak valid. Gunakan format Indonesia: 08xxxxxxxxx atau 628xxxxxxxxx'])
                    ->withInput();
            }

            $req->validate([
                'full_name' => 'required|string|max:150',
                'phone_number' => 'required|string',
                'email' => 'nullable|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
            ], [
                'full_name.required' => 'Nama lengkap wajib diisi.',
                'full_name.max' => 'Nama lengkap maksimal 150 karakter.',
                'phone_number.required' => 'Nomor telepon wajib diisi.',
                'email.email' => 'Format alamat email tidak valid.',
                'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain',
                'password.required' => 'Kata sandi wajib diisi.',
                'password.min' => 'Kata sandi minimal 6 karakter.',
                'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            ]);

            // Normalize phone number to 628 format for storage
            $normalizedPhone = $this->normalizeIndonesianPhone($req->phone_number);

            // Check if normalized phone number already exists
            $existingUser = User::where('phone_number', $normalizedPhone)->first();
            if ($existingUser) {
                return redirect()->back()->withErrors(['phone_number' => 'Nomor telepon sudah terdaftar.'])->withInput();
            }

            // Check if email already exists with OTP status
            $emailExists = User::where('email', $req->email)->first();
            if ($emailExists && $emailExists->isOtp()) {
                // Redirect to OTP verification page
                auth()->guard('web')->login($emailExists);
                return redirect()->route('otp.verify')->with('info', 'Email Anda sudah terdaftar. Silakan verifikasi email Anda terlebih dahulu.');
            }

            // Start database transaction for atomic registration
            DB::beginTransaction();

            try {
                $user = User::create([
                    'full_name' => $req->full_name,
                    'phone_number' => $normalizedPhone, // Store normalized phone number
                    'email' => $req->email, // Now optional
                    'password_hash' => \Hash::make($req->password),
                    'role' => 'BIDDER',
                    'status' => 'OTP', // Set status to OTP for email verification
                    // NIK and KTP fields will be null for Phase 1 registration
                    'nik' => null,
                    'ktp_photo' => null,
                    'npwp' => null,
                ]);

                // Send OTP email
                $otpController = new \App\Http\Controllers\OtpController();
                $otpController->sendOtp($user);

                // Login the user
                auth()->guard('web')->login($user);

                // Commit the transaction
                DB::commit();

                // Redirect to OTP verification page
                return redirect()->route('otp.verify')->with('success', 'Pendaftaran berhasil! Silakan verifikasi email Anda untuk melanjutkan.');

            } catch (\Exception $transactionException) {
                // Rollback the transaction if any error occurs
                DB::rollBack();
                throw $transactionException;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors with custom alert
            $errors = $e->validator->errors();
            if ($errors->has('phone_number') && str_contains($errors->first('phone_number'), 'sudah terdaftar')) {
                return redirect()->back()->withErrors($e->validator)->withInput()->with('error', 'Nomor telepon sudah terdaftar, silakan gunakan nomor lain');
            } elseif ($errors->has('phone_number') && str_contains($errors->first('phone_number'), 'tidak valid')) {
                return redirect()->back()->withErrors($e->validator)->withInput()->with('error', 'Format nomor telepon tidak valid. Gunakan format Indonesia: 08xxxxxxxxx atau 628xxxxxxxxx');
            } elseif ($errors->has('email') && str_contains($errors->first('email'), 'sudah terdaftar')) {
                return redirect()->back()->withErrors($e->validator)->withInput()->with('error', 'Email sudah terdaftar, silakan gunakan email lain');
            } else {
                return redirect()->back()->withErrors($e->validator)->withInput()->with('error', 'Terdapat kesalahan dalam form pendaftaran. Silakan periksa kembali.');
            }
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    // Admin Authentication Methods
    public function adminLoginPage() {
        return view('auth.admin-login');
    }

    public function loginAdmin(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $email = $req->get('email');
        $password = $req->get('password');

        $user = User::where('email', $email)->first();

        // Check if user exists and is an admin
        if (!$user) {
            return redirect()->back()->withInput($req->only('email'))->with('error', 'Email tidak ditemukan dalam sistem');
        }

        if ($user->role !== 'ADMIN') {
            return redirect()->back()->withInput($req->only('email'))->with('error', 'Akses ditolak. Hanya admin yang dapat masuk melalui halaman ini.');
        }

        if ($user->status === 'SUSPENDED') {
            return redirect()->back()->withInput($req->only('email'))->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.');
        }

        if ($user->status === 'REJECTED') {
            return redirect()->back()->withInput($req->only('email'))->with('error', 'Akun Anda telah ditolak. Silakan hubungi administrator.');
        }

        if (\Hash::check($password, $user->password_hash)) {
            auth()->guard('web')->login($user);
            session(["email" => $email]);
            session(['last_activity' => time()]); // Set initial session activity timestamp

            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang di panel admin!');
        } else {
            return redirect()->back()->withInput($req->only('email'))->with('error', 'Kata sandi yang Anda masukkan salah');
        }
    }

    public function logout() {
        session()->flush();
        Auth::logout();
        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar dari akun. Sampai jumpa!');
    }

    /**
     * Show the forgot password form.
     */
    public function forgotPasswordPage()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link.
      */
     public function sendResetLink(Request $request)
     {
         $request->validate([
             'phone_number' => 'required|string|exists:users,phone_number'
         ], [
             'phone_number.required' => 'Nomor telepon wajib diisi.',
             'phone_number.exists' => 'Nomor telepon tidak terdaftar dalam sistem.'
         ]);

         // For now, we'll use a simple approach - in production you'd want to send SMS
         // Since we don't have SMS integration yet, we'll create a temporary reset token
         $user = User::where('phone_number', $request->phone_number)->first();

         if ($user && $user->email) {
             $status = Password::sendResetLink(
                 ['email' => $user->email]
             );

             if ($status === Password::RESET_LINK_SENT) {
                 return back()->with('success', 'Link reset password telah dikirim ke email Anda yang terdaftar.');
             }
         }

         return back()->withErrors(['phone_number' => 'Gagal mengirim link reset password. Pastikan email Anda terdaftar.']);
     }

    /**
     * Show the reset password form.
     */
    public function resetPasswordPage(Request $request, $token = null)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    /**
     * Reset the password.
      */
     public function resetPassword(Request $request)
     {
         $request->validate([
             'token' => 'required',
             'phone_number' => 'required|string',
             'password' => 'required|min:6|confirmed',
         ], [
             'phone_number.required' => 'Nomor telepon wajib diisi.',
             'password.required' => 'Password wajib diisi.',
             'password.min' => 'Password minimal 6 karakter.',
             'password.confirmed' => 'Konfirmasi password tidak cocok.',
         ]);

         // Find user by phone number and get their email for password reset
         $user = User::where('phone_number', $request->phone_number)->first();

         if (!$user || !$user->email) {
             return back()->withErrors(['phone_number' => 'Nomor telepon tidak ditemukan atau tidak memiliki email terdaftar.']);
         }

         $status = Password::reset(
             array_merge($request->only('password', 'password_confirmation', 'token'), ['email' => $user->email]),
             function (User $user, string $password) {
                 $user->forceFill([
                     'password_hash' => Hash::make($password)
                 ])->setRememberToken(Str::random(60));

                 $user->save();

                 event(new PasswordReset($user));
             }
         );

         if ($status === Password::PASSWORD_RESET) {
             return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
         }

         return back()->withErrors(['phone_number' => 'Token reset password tidak valid atau sudah kadaluarsa.']);
     }

     /**
      * Normalize Indonesian phone number to 628 format
      */
     private function normalizeIndonesianPhone($phone)
     {
         // Remove all non-numeric characters
         $phone = preg_replace('/\D/', '', $phone);

         // Handle different formats
         if (str_starts_with($phone, '08')) {
             // Convert 08xxxxxxxxx to 628xxxxxxxxx
             return '62' . substr($phone, 1);
         } elseif (str_starts_with($phone, '8')) {
             // Convert 8xxxxxxxxx to 628xxxxxxxxx
             return '62' . $phone;
         } elseif (str_starts_with($phone, '628')) {
             // Already in correct format
             return $phone;
         } elseif (str_starts_with($phone, '+628')) {
             // Remove + and keep 628 format
             return substr($phone, 1);
         }

         // If no pattern matches, return as-is (shouldn't happen due to validation)
         return $phone;
     }
}

