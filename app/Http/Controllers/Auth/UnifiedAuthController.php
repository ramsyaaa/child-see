<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UnifiedAuthController extends Controller
{
    /**
     * Show the unified login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user());
        }
        
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = $request->input('email');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Determine if the input is an email or username
        $fieldType = filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $fieldType => $loginField,
            'password' => $password,
        ];

        // Attempt to authenticate
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if user is active
            if (strtolower($user->status) !== 'active') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is not active. Please contact support.',
                ])->withInput($request->only('email'));
            }

            // Redirect based on role
            return $this->redirectToDashboard($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Show the registration form
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user());
        }
        
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'username' => 'required|string|max:150|unique:users,username',
            'email' => 'required|email|max:150|unique:users,email',
            'phone' => 'required|string|max:50',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'full_name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'MEMBER',
            'status' => 'ACTIVE',
        ]);

        // Auto login after registration
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('member.dashboard')
            ->with('success', 'Akun berhasil dibuat! Selamat datang di Child See.');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Redirect user to appropriate dashboard based on role
     */
    protected function redirectToDashboard($user)
    {
        $role = strtolower($user->role);
        
        return match($role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin' => redirect()->route('member.dashboard'),
            'member' => redirect()->route('member.dashboard'),
            default => redirect()->route('member.dashboard'),
        };
    }

    /**
     * Show forgot password form (placeholder)
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }
}

