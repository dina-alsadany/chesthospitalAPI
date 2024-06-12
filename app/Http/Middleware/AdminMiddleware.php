<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Check if the user is an admin
        if (auth()->user() && auth()->user()->EmployeeType === 'admin') {
            return $next($request);
        }

        // If not an admin, return unauthorized response
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
