<?php

namespace POS\AbsPos\App\Providers;

use Illuminate\Support\ServiceProvider;

class AbsPosServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register views under "abspos::" namespace
        $this->loadViewsFrom(
            __DIR__ . '/../../Resources/views',
            'abspos'
        );
    }
}
