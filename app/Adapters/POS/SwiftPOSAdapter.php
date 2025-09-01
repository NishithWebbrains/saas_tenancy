<?php 
namespace App\Adapters\POS;

class SwiftPOSAdapter implements POSAdapter {
    public function fetchProducts($store) {
        // Call SwiftPOS API, map response to internal format
    }
    public function syncStock($store) { /* ... */ }
    public function syncSales($store) { /* ... */ }
}
