<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Adapters\POS\POSAdapter;  // Import your adapter interface

class POSSyncController extends Controller
{
    protected $adapter;

    // Constructor injects the adapter implementation automatically
    public function __construct(POSAdapter $adapter)
    {
        $this->adapter = $adapter;  // Laravel injects SwiftPOSAdapter here
    }

    // Example sync method called on POS sync POST requests
    public function sync(Request $request)
    {
        $tenant = app('currentTenant'); // Assuming middleware sets this

        // Use adapter to fetch or sync data for the current store/tenant
        $products = $this->adapter->fetchProducts($tenant);

        // TODO: persist $products to tenant DB, dispatch jobs, etc.

        return response()->json(['message' => 'POS sync successful']);
    }
}
