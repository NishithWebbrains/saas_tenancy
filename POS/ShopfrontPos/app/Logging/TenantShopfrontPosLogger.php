<?php

namespace POS\ShopfrontPos\App\Logging;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

use POS\ShopfrontPos\App\Models\TenantDetail;

class TenantShopfrontPosLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $tenant     = tenant(); // Stancl tenant object
        $tenantId   = $tenant?->id ?? 'unknown';
        $tenantName = $tenantId; // fallback

        try {
            // Fetch tenant name from tenant DB (TenantDetail table)
            $tenantDetails = TenantDetail::all();
            $dbName = $tenantDetails->pluck('name')->first();

            if (!empty($dbName)) {
                $tenantName = $dbName;
            }
        } catch (\Throwable $e) {
            // If tenant DB not available, fallback stays as ID
        }

        // Sanitize both
        $safeTenantName = preg_replace('/[^A-Za-z0-9_\-]/', '_', strtolower($tenantName));
        $safeTenantId   = preg_replace('/[^A-Za-z0-9_\-]/', '_', (string) $tenantId);

        $logDir = base_path("POS/ShopfrontPos/storage/logs");

        if (!is_dir($logDir)) {
            mkdir($logDir, 0775, true);
        }

        // File pattern: tenantID_tenantName_laravel.log (rotated daily)
        $logFile = "{$logDir}/{$safeTenantName}_{$safeTenantId}_laravel.log";

        $logger = new Logger("{$safeTenantName}_{$safeTenantId}");

        $handler = new RotatingFileHandler(
            $logFile,
            $config['days'] ?? 14, // keep 14 days
            Logger::toMonologLevel($config['level'] ?? 'debug')
        );

        $logger->pushHandler($handler);

        return $logger;
    }
}
