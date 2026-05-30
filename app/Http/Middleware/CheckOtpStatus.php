<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckOtpStatus
{
    /**
     * Handle an incoming request.
     *
     * Redirects users with OTP status to the OTP verification page.
     * This middleware ensures that users cannot access protected routes
     * until they have verified their email via OTP.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // If user has OTP status, redirect to OTP verification page
            if ($user->isOtp()) {
                return redirect()->route('otp.verify')->with('info', 'Silakan verifikasi email Anda terlebih dahulu untuk melanjutkan.');
            }
        }

        return $next($request);
    }
}
