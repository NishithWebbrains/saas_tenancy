<?php

namespace POS\SwiftPos\App\Providers;

use Illuminate\Support\ServiceProvider;

class SwiftPosServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register views under "swiftpos::" namespace
        $this->loadViewsFrom(
            __DIR__ . '/../../Resources/views',
            'swiftpos'
        );
    }
}
