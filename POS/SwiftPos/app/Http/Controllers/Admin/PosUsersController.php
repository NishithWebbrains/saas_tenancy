<?php

namespace POS\SwiftPos\App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use POS\SwiftPos\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tenant;
use App\Models\Tenant\TenantUser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PosUsersController extends Controller
{
    protected $tenantId;

    public function __construct()
    {
        // Initialize tenantId from the request
        $this->tenantId = request()->route('tenant')
            ?? request()->segment(1)
            ?? request()->input('tenant')
            ?? null;
    }

    public function view()  
    {
        \Log::channel('tenant_swiftpos')->info('SwiftPOS action executed', [
            'tenant_id' => tenant('id'),
        ]);

       return view('swiftpos::layouts.posusers');
    //    return redirect()->route('stores.index')
    }

    public function createuser()
    {
       //dd($tenants);
       $tenantId = $this->tenantId;
       $tenant   = Tenant::findOrFail($tenantId);
       $roles = $tenant->run(function () {
        return \App\Models\Tenant\Role::all(); // fetch roles from tenant DB
    });
        return view('swiftpos::layouts.createuser',[
            'roles'    => $roles,
        ]);
    }

    /**
     * Create user and assign to tenants.
     */
    public function storeuser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:tenant_users,email',
            'password' => 'required|min:6',
            'role'     => 'required|integer', // role_id, coming from roles table
        ]);

        $tenantId = $this->tenantId;
        $tenant   = Tenant::findOrFail($tenantId);

        // Resolve tenant-specific DB connection
        $tenantDbName = $tenant->tenancy_db_name;
        $defaultConnection = config('database.connections.mysql');
        $dynamicConnection = $defaultConnection;
        $dynamicConnection['database'] = $tenantDbName;
        config(["database.connections.tenant_dynamic" => $dynamicConnection]);

        $posType = DB::connection('tenant_dynamic')
            ->table('tenant_details')
            ->value('pos_type');

        $tenant->run(function () use ($request, $posType) {
            TenantUser::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'pos_type' => $posType,
                'role_id'  => $request->role, // ✅ directly assign role_id
            ]);
        });

        return redirect()->route('swiftpos.posusers', ['tenant' => $tenantId])
                        ->with('success', 'User created and role assigned successfully.');
    }


    public function storeusersdata()
    {
        \Log::info('storeusersdata method called.');
        $tenantId = $this->tenantId;
        // Get the tenant ID from the request
        $tenantId = request()->route('tenant')
            ?? request()->segment(1)
            ?? request()->input('tenant')
            ?? null;

        \Log::info('Tenant ID retrieved: ', ['tenantId' => $tenantId]);

        // Find the tenant
        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            \Log::error('Tenant not found.', ['tenantId' => $tenantId]);
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        \Log::info('Tenant found: ', ['tenant' => $tenant]);

        // Query the tenant_users table in the tenant's database
        $users = [];
        $tenant->run(function () use (&$users) {
            \Log::info('Switching to tenant database.');
            $users = DB::table('tenant_users')->get();
            \Log::info('Users retrieved from tenant_users table.', ['users' => $users]);
        });

        \Log::info('Preparing DataTables response.');

        // Use DataTables to format the data
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('actions', function ($user) {
                return '<a href="#" class="btn btn-sm btn-primary">Edit</a>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    
}
