<?php

namespace POS\SwiftPos\App\Logging;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class TenantSwiftPosLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        
        $tenantId = tenant('id') ?? 'unknown'; // Stancl Tenancy helper
        $path = base_path("POS/SwiftPos/storage/logs/{$tenantId}_laravel.log");

        // Ensure directory exists
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0775, true);
        }

        $logger = new Logger("tenant_swiftpos_{$tenantId}");
        $logger->pushHandler(new StreamHandler($path, Logger::toMonologLevel($config['level'])));

        return $logger;
    }
}
