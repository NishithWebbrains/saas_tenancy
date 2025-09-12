<?php

namespace POS\AbsPos\App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use POS\AbsPos\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tenant;
use App\Models\Tenant\TenantUser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PosRolesController extends Controller
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
        \Log::channel('tenant_abspos')->info('AbsPos action executed', [
            'tenant_id' => tenant('id'),
        ]);

       return view('abspos::layouts.roles');
    //    return redirect()->route('stores.index')
    }

    public function createrole()
    {
       //dd($tenants);
        return view('abspos::layouts.createrole');
    }

    /**
     * Create user and assign to tenants.
     */
    public function addrole(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:roles,name',
        ]);

        $tenantId = $this->tenantId;
        $tenant   = Tenant::find($tenantId);

        // Setup tenant DB connection
        $tenantDbName = $tenant->tenancy_db_name;
        $defaultConnection = config('database.connections.mysql');
        $dynamicConnection = $defaultConnection;
        $dynamicConnection['database'] = $tenantDbName;
        config(["database.connections.tenant_dynamic" => $dynamicConnection]);

        // Create role inside tenant DB
        $tenant->run(function () use ($request) {
            \App\Models\Tenant\Role::create([
                'name'        => $request->name,
            ]);
        });

        return redirect()->route('abspos.roles', ['tenant' => $tenantId])
                        ->with('success', 'Role created successfully.');
    }

    public function roledata()
    {
        \Log::info('roledata method called.');
    
        // get tenant id from request (same approach as your other methods)
        $tenantId = request()->route('tenant')
            ?? request()->segment(1)
            ?? request()->input('tenant')
            ?? null;
    
        \Log::info('Tenant ID retrieved: ', ['tenantId' => $tenantId]);
    
        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            \Log::error('Tenant not found.', ['tenantId' => $tenantId]);
            return response()->json(['error' => 'Tenant not found'], 404);
        }
    
        $roles = collect();
        $tenant->run(function () use (&$roles) {
            \Log::info('Switching to tenant database.');
            $roles = DB::table('roles')->get();
            \Log::info('Roles retrieved from roles table.', ['roles' => $roles]);
        });
    
        \Log::info('Preparing DataTables response.');
    
        // IMPORTANT: bring $tenantId into the closure with "use ($tenantId)"
        return DataTables::of($roles)
            ->addIndexColumn()
            ->addColumn('actions', function ($role) use ($tenantId) {
                // adjust route names and params to match your app
                $editUrl = '';
                $permUrl = route('abspos.permission', ['tenant' => $tenantId, 'role' => $role->id]);
    
                $btn  = '<a href="'.$editUrl.'" class="btn btn-sm btn-primary mr-1">Edit</a>';
                $btn .= '<a href="'.$permUrl.'" class="btn btn-sm btn-success">Permission</a>';
    
                return $btn;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    


    
}
