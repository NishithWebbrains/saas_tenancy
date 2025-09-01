<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Factories\POSAdapterFactory;

class POSAdapterServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind factory for resolving adapters
        $this->app->singleton(POSAdapterFactory::class, function ($app) {
            return new POSAdapterFactory();
        });
    }
}

