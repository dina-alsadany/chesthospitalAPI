<?php

namespace App\Http;

use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\CheckRole::class,
        \App\Http\Middleware\CorsMiddleware::class,

    ];

    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            HandleCors::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
        ],
    ];

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.api' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
        'api' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'role' => \App\Http\Middleware\CheckRole::class,
        'admin' => \App\Http\Middleware\CheckAdministrator::class,
    'doctor' => \App\Http\Middleware\CheckDoctor::class,
'employeeType' => \App\Http\Middleware\CheckEmployeeType::class,
'receptionist' => \App\Http\Middleware\CheckReceptionist::class,
'pharmacy' => \App\Http\Middleware\CheckPharmacy::class,

'CheckRole' => \App\Http\Middleware\CheckRole::class,
'radiologist' => \App\Http\Middleware\CheckRadiologist::class,
'lab' => \App\Http\Middleware\CheckLab::class,
'lab-admin' => \App\Http\Middleware\CheckLabAdmin::class,
'nurse-admin' => \App\Http\Middleware\CheckNurseAdmin::class,
'radiologist-admin' => \App\Http\Middleware\CheckRadiologistAdmin::class,





];

    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
