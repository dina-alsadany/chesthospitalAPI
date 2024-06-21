<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{ /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
    * @param  string|null  ...$roles
    * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::guard('api')->user();
        if ($user && $user instanceof \App\Models\Account && method_exists($user, 'role')) {

        if ($user && method_exists($user, 'hasAnyRole')) {
            if ($user->hasAnyRole($roles)) {
                return $next($request);
            } else {
                return response()->json(['message' => 'User does not have the required roles'], 403);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }}
}
