<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPharmacy
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('api')->user();

        if ($user && $user instanceof \App\Models\Account && method_exists($user, 'isPharmacy')) {
            if ($user->isPharmacy()) {
                return $next($request);
            } else {
                return response()->json(['message' => 'User is not a Pharmacy'], 403);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
