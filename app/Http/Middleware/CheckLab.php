<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLab
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('api')->user();

        if ($user && $user instanceof \App\Models\Account && method_exists($user, 'isLab')) {
            if ($user->isLab()) {
                return $next($request);
            } else {
                return response()->json(['message' => 'User is not a lab'], 403);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
