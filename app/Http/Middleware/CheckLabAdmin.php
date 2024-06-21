<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckLabAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('api')->user();

        if ($user && $user instanceof \App\Models\Account && method_exists($user, 'isLabAdmin')) {
            if ($user->isLabAdmin()) {
                return $next($request);
            } else {
                return response()->json(['message' => 'User is not a lab admin'], 403);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
