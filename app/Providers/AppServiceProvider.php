<?php

namespace App\Providers;

use App\Models\Adminstrator;
use App\Http\Middleware\CheckLab;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckDoctor;
use App\Http\Middleware\CheckLabAdmin;
use App\Http\Middleware\CheckPharmacy;
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\CheckNurseAdmin;
use App\Http\Middleware\CheckRadiologist;
use App\Http\Middleware\CheckReceptionist;
use App\Http\Middleware\CheckAdministrator;
use App\Http\Middleware\CheckNurseSchedule;
use App\Http\Middleware\CheckRadiologistAdmin;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('admin', function($app) {
            return new CheckAdministrator();
            // Replace AdminClass with your actual class
        });
        $this->app->bind('doctor', function($app) {
            return new CheckDoctor();
            // Replace AdminClass with your actual class
        });
        $this->app->bind('receptionist', function($app) {
            return new CheckReceptionist();
            // Replace AdminClass with your actual class
        });
        $this->app->bind('nurse', function($app) {
            return new CheckNurseSchedule();
            // Replace AdminClass with your actual class
        });
        $this->app->bind('pharmacy', function($app) {
            return new CheckPharmacy();
            // Replace AdminClass with your actual class
        });
        $this->app->bind('radiologist', function($app) {
            return new CheckRadiologist();
            // Replace AdminClass with your actual class
        });
        $this->app->bind('CheckRole', function($app) {
            return new CheckRole();
            // Replace AdminClass with your actual class
        });
        $this->app->bind('lab', function($app) {
            return new CheckLab();
            // Replace AdminClass with your actual class
        });
        $this->app->bind('lab-admin', function($app) {
            return new CheckLabAdmin();
            // Replace AdminClass with your actual class
        });
        $this->app->bind('nurse-admin', function($app) {
            return new CheckNurseAdmin();
            // Replace AdminClass with your actual class
        });
        $this->app->bind('radiologist-admin', function($app) {
            return new CheckRadiologistAdmin();
            // Replace AdminClass with your actual class
        });
    }



    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
