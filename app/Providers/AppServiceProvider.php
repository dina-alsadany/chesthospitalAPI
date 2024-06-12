<?php

namespace App\Providers;

use App\Models\Adminstrator;
use App\Http\Middleware\CheckDoctor;
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\CheckAdministrator;
use App\Http\Middleware\CheckNurseSchedule;
use App\Http\Middleware\CheckPharmacy;
use App\Http\Middleware\CheckReceptionist;

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
    }



    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
