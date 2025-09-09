<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Store;

class DashboardController extends Controller
{
    public function index()
{
    try {
        $query = Store::query();

        // Apply filter for storeadmin role if necessary
        if (auth()->user()->hasRole('storeadmin')) {
            $query->where('owner_user_id', auth()->id());
        }

        // Get the count of stores
        $storeCount = $query->count();

        return view('layouts.admin.dashboard', compact('storeCount'));
    } catch (\Exception $e) {
        \Log::error('Dashboard error: '.$e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);
        return redirect()->back()->with('error', 'Unable to load dashboard.');
    }
}
}
