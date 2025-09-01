<?php 
namespace App\Adapters\POS;

class SwiftPOSAdapter implements POSAdapter {
    public function fetchProducts($store) {
        // Call SwiftPOS API, map response to internal format
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $store->swiftpos_api_token,
        ])->get('https://api.swiftpos.com/products');
    
        if ($response->successful()) {
            // Map SwiftPOS API product data to your format
            return $response->json(); // Or transform as needed
        }
    
        // Handle errors or return empty
        return [];
    }
    public function syncStock($store) { /* ... */ }
    public function syncSales($store) { /* ... */ }
}
