<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Adapters\POS\POSAdapter;
use App\Adapters\POS\SwiftPOSAdapter;

class POSAdapterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        // Bind the interface to the implementation
        $this->app->bind(POSAdapter::class, SwiftPOSAdapter::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // You usually leave this empty when only binding in register()
    }
}
