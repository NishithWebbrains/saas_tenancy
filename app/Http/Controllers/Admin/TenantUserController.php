<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Store;
use App\Models\Tenant;
use App\Models\Tenant\TenantUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Yajra\DataTables\Facades\DataTables;

class TenantUserController extends Controller
{
    /**
     * Show tenants owned by this storeadmin (for dropdown).
     */
    public function createuser()
    {
       //$tenants = auth()->user()->tenants; 
       $tenants = auth()->user()->tenants; // Central connection
       $tenantList = [];
   
       foreach ($tenants as $tenant) {
           // Switch to tenant DB connection (using tenancy-for-laravel)
           tenancy()->initialize($tenant);
   
           // Manually fetch details from tenant DB
           $details = \App\Models\Tenant\TenantDetail::first();
           
           // Prepare each tenant for view
           $tenantList[] = [
               'id' => $tenant->id,
               'name' => $details ? $details->name : 'No Name',
               // Add other desired properties here
           ];
   
           tenancy()->end(); // Restore central connection
       }
       //dd($tenants);
        return view('layouts.admin.stores.createuser', compact('tenantList'));
    }

    public function viewusers()
    {
       //$tenants = auth()->user()->tenants; 
       //dd($tenants);
        return view('layouts.admin.stores.indexstoreusers');
    }
    public function storeusersdata()
    {
        // Base query: all central users
        $query = \App\Models\User::query();

        // Restrict to storeadmin’s users if needed
        if (auth()->user()->hasRole('storeadmin')) {
            $allowedUserIds = [];

            // Stores owned by this admin
            $stores = Store::where('owner_user_id', auth()->id())->get();

            foreach ($stores as $store) {
                $tenant = \App\Models\Tenant::find($store->tenant_id);

                if ($tenant) {
                    $tenant->run(function () use (&$allowedUserIds) {
                        $ids = DB::table('tenant_users')->pluck('user_id')->toArray();
                        $allowedUserIds = array_merge($allowedUserIds, $ids);
                    });
                }
            }

            // Filter users
            if (!empty($allowedUserIds)) {
                $query->whereIn('id', $allowedUserIds);
            } else {
                // If no users, make query return nothing
                $query->whereRaw('0 = 1');
            }
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('stores', function ($user) {
        $stores = [];

        foreach (\App\Models\Tenant::all() as $tenant) {
            // ✅ Get store from central DB first
            $store = Store::where('tenant_id', $tenant->id)->first();

            if (! $store) {
                continue; // skip if no store for this tenant
            }

            // ✅ Then check inside tenant DB if user is assigned
            $tenant->run(function () use ($tenant, $user, $store, &$stores) {
                $assigned = DB::table('tenant_users')
                    ->where('user_id', $user->id)
                    ->exists();

                if ($assigned) {
                    $stores[] = $store->name; // central store name
                }
            });
        }

        return empty($stores) ? '—' : implode(', ', $stores);
        })

            ->make(true);
    }


    /**
     * Create user and assign to tenants.
     */
    public function storeuser(Request $request)
    {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'role'     => 'required|string',
                'tenants'  => 'required|array',
            ]);

            // Step 1: Create central user
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->assignRole($request->role);

            
            // Step 2: Assign user into selected tenant databases
        foreach ($request->tenants as $tenantId) {
            $tenant = Tenant::find($tenantId);
                //taking tenants pos_type...
                $tenantDbName = $tenant->tenancy_db_name;
                $defaultConnection = config('database.connections.mysql');
                $dynamicConnection = $defaultConnection;
                $dynamicConnection['database'] = $tenantDbName;
                config(["database.connections.tenant_dynamic" => $dynamicConnection]);

                // 2. Query tenant_details table in the dynamic DB
                $posType = DB::connection('tenant_dynamic')
                    ->table('tenant_details')
                    ->value('pos_type');
          
            if (!$tenant) {
                \Log::warning("⚠️ Tenant not found", ['tenant_id' => $tenantId]);
                continue;
            }
            $tenant->run(function () use ($request, $tenant,$posType) {
                TenantUser::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'pos_type' => $posType,
                    'roles' => [$request->role],
                ]);
            });
            \Log::info("✅ Created tenant user", ['id' => $user->id, 'email' => $user->email]);


            // \Log::info("➡️ Assigning user to tenant", ['tenant_id' => $tenant->id]);
        }
        return redirect()->route('stores.index')
                         ->with('success', 'User created in central DB and assigned to selected tenants.');
    }


}
