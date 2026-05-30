<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Redirect unauthenticated users to unified login page
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user has the required role (case-insensitive)
        if (strtolower($user->role) !== strtolower($role)) {
            abort(403, 'Unauthorized access. You do not have permission to access this area.');
        }

        // Check if user account is active (case-insensitive)
        if (strtolower($user->status) !== 'active') {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Your account is not active. Please contact support.');
        }

        return $next($request);
    }
}
