<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SuperadminAuthController extends Controller
{
    /**
     * Show the superadmin login form.
     */
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->isSuperadmin()) {
            return redirect()->route('superadmin.dashboard');
        }
        
        return view('auth.superadmin-login');
    }

    /**
     * Handle superadmin login request.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('login', 'remember'));
        }

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
            'role' => 'superadmin',
            'status' => 'active',
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('superadmin.dashboard'))
                ->with('success', 'Welcome back, ' . Auth::user()->full_name . '!');
        }

        return redirect()->back()
            ->withErrors(['login' => 'Invalid credentials or you do not have superadmin access.'])
            ->withInput($request->only('login', 'remember'));
    }

    /**
     * Handle superadmin logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('superadmin.login')
            ->with('success', 'You have been logged out successfully.');
    }
}

