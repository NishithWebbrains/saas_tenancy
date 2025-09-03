<?php

namespace App\Http\Controllers\POS\AbsPos\Admin;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\CreateStoreAndTenantService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StoreController extends Controller
{
    public function index()
    {
       return view('layouts.admin.stores.index');
    }

    public function create()
    {
        return view('layouts.admin.stores.create');
    }

    public function store(Request $request, CreateStoreAndTenantService $service)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'pos_type' => ['required', 'in:shopfrontpos,swiftpos,abspos'],
        ]);

        $payload = [
            'name'          => $data['name'],
            'slug'          => Str::slug($data['name']), // tenant ID + path
            'owner_user_id' => auth()->id(),
            'pos_type'      => $data['pos_type'],
        ];

        $tenant = $service->handle($payload);

        // Map pos_type to route segment (assuming 'shopfrontpos' => 'shopfront', etc.)
        $posRouteSegmentMap = [
            'shopfrontpos' => 'shopfront',
            'swiftpos'    => 'swiftpos',
            'abspos'      => 'abspos',
        ];

        $posSegment = $posRouteSegmentMap[$data['pos_type']] ?? 'shopfront';

        // Build tenant path URL dynamically with POS segment
        $tenantUrl = url('/tenant/' . $tenant->slug . '/' . $posSegment . '/dashboard');

        return redirect()->to($tenantUrl)
            ->with('status', 'Store created and tenant initialized.');
    }



    public function edit(Store $store)
    {
        $this->authorizeStore($store);
        return view('layouts.admin.stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $this->authorizeStore($store);

        $data = $request->validate([
            'name'  => ['required','string','max:255'],
            'slug'  => ['required','alpha_dash','unique:stores,slug,'.$store->id],
        ]);

        $store->update($data);

        return back()->with('status','Store updated');
    }

    public function destroy(Store $store)
    {
        $this->authorizeStore($store);

        // Optional: drop tenant DB too (product decision)
        $store->delete();

        return back()->with('status','Store deleted');
    }

    public function getData()
    {
        $query = Store::query();

        if (auth()->user()->hasRole('storeadmin')) {
            $query->where('owner_user_id', auth()->id());
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('actions', function ($store) {
                $editUrl = route('stores.edit', $store);
                $deleteUrl = route('stores.destroy', $store);

                return '
                    <a href="'.$editUrl.'" class="btn btn-sm btn-warning">Edit</a>
                    <form action="'.$deleteUrl.'" method="POST" style="display:inline-block;">
                        '.csrf_field().method_field('DELETE').'
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm(\'Delete this store?\')">Delete</button>
                    </form>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function show(Store $store)
    {
        abort(404);
    }

    protected function authorizeStore(Store $store): void
    {
        if (auth()->user()->hasRole('superadmin')) return;

        abort_unless($store->owner_user_id === auth()->id(), 403);
    }
}
