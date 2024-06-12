<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDoctor
{

    public function handle($request, Closure $next)
    {
        $user = Auth::guard('api')->user();

        if ($user && $user instanceof \App\Models\Account && method_exists($user, 'isDoctor')) {
            if ($user->isDoctor()) {
                return $next($request);
            } else {
                return response()->json(['message' => 'User is not a doctor'], 403);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }

}
