<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Wevelope\Wevelope\Observer\RefNoObserver;
use App\Libs\LibKernel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Create all folder that neccesery
        LibKernel::init();

        //Ref No Event
    }
}
