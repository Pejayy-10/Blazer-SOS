<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Import Auth

class CheckRole
{
    /**
     * Handle an incoming request.
     * Allows access if user has one of the specified roles.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles  // Accepts one or more role names as parameters
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated AND has one of the required roles
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            // Redirect or abort if user doesn't have the required role
            // Redirecting to home is often friendlier than abort(403)
            // return redirect(route('app.dashboard'))->with('error', 'You do not have permission to access this page.');
            abort(403, 'Unauthorized action.'); // Or show a 403 Forbidden page
        }

        return $next($request);
    }
}