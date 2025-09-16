<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Store;
use App\Models\Tenant\TenantUser;
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
        $tenantId = Str::random(19);
        $payload = [
            'id'          => $tenantId,
            'name'          => $data['name'],
            'slug'          => Str::slug($data['name']), // tenant ID + path
            'owner_user_id' => auth()->id(),
            'pos_type'      => $data['pos_type'],
        ];

        $tenant = $service->handle($payload);

        // Map pos_type to route segment (assuming 'shopfrontpos' => 'shopfront', etc.)
        $posRouteSegmentMap = [
            'shopfrontpos' => 'shopfrontpos',
            'swiftpos'    => 'swiftpos',
            'abspos'      => 'abspos',
        ];

        $posSegment = $posRouteSegmentMap[$data['pos_type']] ?? 'shopfront';

        // Build tenant path URL dynamically with POS segment
        $tenantUrl = url('/' . $tenantId . '/' . $posSegment . '/dashboard');
$store = $tenant;
return redirect()->route('stores.edit', $store);
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
            'clientid' => ['nullable', 'string', 'max:255'],
            'client_password' => ['nullable', 'string', 'max:255'],
            'store_id' => ['nullable', 'string', 'max:255'],
            'external_storeid' => ['nullable', 'string', 'max:255'],
            'shopfrontpos_vendor_identifier' => ['nullable', 'string', 'max:255'],
            'swiftpos_vendor_identifier' => ['nullable', 'string', 'max:255'],
            'abspos_vendor_identifier' => ['nullable', 'string', 'max:255'],

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
        try {
            $query = Store::query();

            if (auth()->user()->hasRole('storeadmin')) {
                $query->where('owner_user_id', auth()->id());
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('users', function ($store) {
                    $tenant = Tenant::find($store->tenant_id);
                    $usersList = '';
                    if ($tenant) {
                        $usersList = $tenant->run(function () {
                            return TenantUser::pluck('email')->implode(', ');
                        });
                    }
                    return $usersList;
                })
                ->addColumn('actions', function ($store) {
                   
                    // Build tenant path URL dynamically with POS segment
                    $view = url('/' . $store['tenant_id'] . '/' . $store['pos_type'] . '/dashboard');
                    $editUrl = route('stores.edit', $store);
                    $deleteUrl = route('stores.destroy', $store);

                    return '
                        <a href="'.$view.'" class="btn btn-sm btn-success">View</a>
                        <a href="'.$editUrl.'" class="btn btn-sm btn-warning">Edit</a>
                        <form action="'.$deleteUrl.'" method="POST" style="display:inline-block;">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm(\'Delete this store?\')">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['users', 'actions'])
                ->make(true);
        } catch (\Exception $e) {
            \Log::error('DataTables stores getData error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            // Optionally return an empty JSON response or error message to AJAX
            return response()->json(['error' => 'Data fetch error. Please check logs.'], 500);
        }
    }
    public function getDataCount()
    {
        try {
            $query = Store::query();

            // Apply the same filter as in getData
            if (auth()->user()->hasRole('storeadmin')) {
                $query->where('owner_user_id', auth()->id());
            }

            // Count the number of items
            $count = $query->count();

            return response()->json(['count' => $count], 200);
        } catch (\Exception $e) {
            \Log::error('DataTables stores getDataCount error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Data count error. Please check logs.'], 500);
        }
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
    

    public function updateZkongCredentials(Request $request, Store $store)
    {
        $this->authorizeStore($store);
        //dd('hu');
        
        $data = $request->validate([
            'clientid' => ['nullable','string', 'max:255'],
            'client_password' => ['nullable','string', 'max:255'],
            'store_id' => ['nullable','string', 'max:255'],
            'external_storeid' => ['nullable','string', 'max:255'],
        ]);
        //dd($request);
        $store->update($data);

        return back()->with('status', 'Zkong credentials updated successfully');
    }

    public function updatePosVendorIdentifier(Request $request, Store $store)
    {
        $this->authorizeStore($store);
        
        // Determine which vendor identifier field to validate by pos_type
        $posType = $store->pos_type;

        $fieldName = $posType.'_vendor_identifier';
        //dd($fieldName);
        $data = $request->validate([
            $fieldName => ['required', 'string', 'max:255'],
        ]);
//dd($data);
        $store->update($data);

        return back()->with('status', ucfirst($posType).' vendor identifier updated successfully');
    }

}
