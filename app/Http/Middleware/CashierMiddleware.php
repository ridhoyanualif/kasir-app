<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CashierMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has the 'cashier' role
        if (Auth::check() && Auth::user()->role === 'cashier') {
            return $next($request);
        }

        // Redirect to a 403 error page or a specific route
        return abort(403, 'Unauthorized action.');
    }
}
