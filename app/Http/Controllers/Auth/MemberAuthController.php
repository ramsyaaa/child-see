<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MemberAuthController extends Controller
{
    /**
     * Show the member login form.
     */
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->isMember()) {
            return redirect()->route('member.dashboard');
        }
        
        return view('auth.member-login');
    }

    /**
     * Handle member login request.
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
            'role' => 'member',
            'status' => 'active',
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('member.dashboard'))
                ->with('success', 'Welcome back, ' . Auth::user()->full_name . '!');
        }

        return redirect()->back()
            ->withErrors(['login' => 'Invalid credentials. Please check your email/username and password.'])
            ->withInput($request->only('login', 'remember'));
    }

    /**
     * Show the member registration form.
     */
    public function showRegisterForm()
    {
        if (Auth::check() && Auth::user()->isMember()) {
            return redirect()->route('member.dashboard');
        }
        
        return view('auth.member-register');
    }

    /**
     * Handle member registration request.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $user = User::create([
            'full_name' => $request->full_name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'member',
            'status' => 'active',
        ]);

        Auth::login($user);

        return redirect()->route('member.dashboard')
            ->with('success', 'Selamat datang di InkluSyncID! Your account has been created successfully.');
    }

    /**
     * Handle member logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('member.login')
            ->with('success', 'You have been logged out successfully.');
    }
}

