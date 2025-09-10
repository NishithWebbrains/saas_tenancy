<?php

namespace POS\ShopfrontPos\App\Providers;

use Illuminate\Support\ServiceProvider;

class ShopfrontPosServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register views under "shopfrontpos::" namespace
        $this->loadViewsFrom(
            __DIR__ . '/../../Resources/views',
            'shopfrontpos'
        );
    }
}
