<?php
namespace App\Adapters\POS;

interface POSAdapter {
    public function fetchProducts($store);
    public function syncStock($store);
    public function syncSales($store);
}
