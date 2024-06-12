<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckEmployeeType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $type
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $type)
    {
        $user = Auth::user();

        // Retrieve the employee record
        $employee = \App\Models\Employee::where('Email', $user->acc_email)->first();

        if (!$employee || $employee->EmployeeType !== $type) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Unauthorized access.',
            ], 403);
        }

        return $next($request);
    }
}
