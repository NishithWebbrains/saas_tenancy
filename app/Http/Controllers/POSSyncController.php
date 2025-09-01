<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factories\POSAdapterFactory;

class POSSyncController extends Controller
{
    protected $adapterFactory;

    public function __construct(POSAdapterFactory $adapterFactory)
    {
        $this->adapterFactory = $adapterFactory;
    }

    public function sync(Request $request, string $tenantPath)
    {
        // Determine which POS to use from request or tenant config
        $tenant = app('currentTenant'); // assumes middleware set this
        $posName = $tenant->pos_system; // e.g., 'swiftpos', 'shopfrontpos', 'abspos'

        try {
            $adapter = $this->adapterFactory->make($posName);

            // Fetch products from POS and persist
            $products = $adapter->fetchProducts($tenant);
            // Persist products with repositories/jobs here...

            return response()->json(['message' => 'POS sync successful', 'data' => $products]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

