<?php 
namespace App\Adapters\POS;

use Illuminate\Support\Facades\Http;

class ABSPOSAdapter implements POSAdapter {
    public function fetchProducts($store) {
        // API call to ABS POS
        $response = Http::get('https://api.absspos.com/products', [
            'api_key' => $store->abspos_key,
            'store_id' => $store->abspos_store_id,
        ]);

        return $response->successful() ? $this->transform($response->json()) : [];
    }

    protected function transform($data) {
        // Transform ABS POS API response to your internal product format
        // ...
        return $data;
    }

    public function syncStock($store) { /* implement */ }
    public function syncSales($store) { /* implement */ }
}
