<?php

namespace POS\SwiftPos\App\Logging;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

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
        $logDir = base_path("POS/SwiftPos/storage/logs");

        // Ensure directory exists
        if (!is_dir($logDir)) {
            mkdir($logDir, 0775, true);
        }

        // File pattern: tenantID_laravel.log (rotated daily)
        $logFile = "{$logDir}/tenant_{$tenantId}_laravel.log";

        $logger = new Logger("{$tenantId}");

        // keep 14 days of logs (you can change 14)
        $handler = new RotatingFileHandler(
            $logFile,
            $config['days'] ?? 14,
            Logger::toMonologLevel($config['level'] ?? 'debug')
        );

        $logger->pushHandler($handler);

        return $logger;
    }
}
