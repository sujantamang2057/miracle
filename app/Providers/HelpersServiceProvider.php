<?php

/**
 * HelpersServiceProvider.php
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelpersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        foreach (glob(base_path('Modules') . '/Common/app/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }
}
