<?php
namespace App\Factories;

use App\Adapters\POS\SwiftPOSAdapter;
use App\Adapters\POS\ShopfrontPOSAdapter;
use App\Adapters\POS\ABSPOSAdapter;
use InvalidArgumentException;

class POSAdapterFactory
{
    public static function make(string $posName)
    {
        return match (strtolower($posName)) {
            'swiftpos' => app(SwiftPOSAdapter::class),
            'shopfrontpos' => app(ShopfrontPOSAdapter::class),
            'abspos' => app(ABSPOSAdapter::class),
            default => throw new InvalidArgumentException("POS adapter for {$posName} not found"),
        };
    }
}
