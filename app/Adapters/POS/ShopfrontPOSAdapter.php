<?php 
namespace App\Adapters\POS;

use Illuminate\Support\Facades\Http;

class ShopfrontPOSAdapter implements POSAdapter {
    public function fetchProducts($store) {
        // API call to ShopfrontPOS
        $response = Http::withHeaders([
            'X-API-KEY' => $store->shopfront_api_key,
        ])->get('https://api.shopfront.com/v1/products');

        return $response->successful() ? $this->mapProducts($response->json()) : [];
    }

    protected function mapProducts($data) {
        // Map ShopfrontPOS response to your format
        // ...
        return $data;
    }

    public function syncStock($store) { /* implement */ }
    public function syncSales($store) { /* implement */ }
}
