<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRadiologist
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('api')->user();

        if ($user && $user instanceof \App\Models\Account && method_exists($user, 'isRadiologist')) {
            if ($user->isRadiologist()) {
                return $next($request);
            } else {
                return response()->json(['message' => 'User is not a Radiologist'], 403);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
