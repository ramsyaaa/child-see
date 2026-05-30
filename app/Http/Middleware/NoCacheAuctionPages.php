<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoCacheAuctionPages
{
    /**
     * Handle an incoming request.
     * 
     * This middleware prevents caching of auction pages to ensure that
     * countdown timers always show the latest status from the server.
     * This is critical to prevent infinite reload loops when countdown reaches zero.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add cache control headers to prevent browser caching
        $response->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', '0');

        return $response;
    }
}

