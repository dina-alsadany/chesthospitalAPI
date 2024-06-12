<?php

namespace App\Http\Middleware;
use Closure;

use App\Models\Employee;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdministrator
{

    public function handle($request, Closure $next)
    {
        $user = Auth::guard('api')->user();

        if ($user && $user instanceof \App\Models\Account && method_exists($user, 'isAdmin')) {
            if ($user->isAdmin()) {
                return $next($request);
            } else {
                return response()->json(['message' => 'User is not admin'], 403);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
