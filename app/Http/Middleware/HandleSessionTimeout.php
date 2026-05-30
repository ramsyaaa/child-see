<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class HandleSessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $lastActivity = Session::get('last_activity');
            $sessionLifetime = config('session.lifetime') * 60; // Convert minutes to seconds

            if ($lastActivity && (time() - $lastActivity) > $sessionLifetime) {
                // Session has expired
                Auth::logout();
                Session::flush();
                Session::regenerate();

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Session expired. Please login again.',
                        'redirect' => route('login')
                    ], 401);
                }

                return redirect()->route('login')
                    ->with('error', 'Your session has expired. Please login again.');
            }

            // Update last activity timestamp
            Session::put('last_activity', time());
        }

        return $next($request);
    }
}
