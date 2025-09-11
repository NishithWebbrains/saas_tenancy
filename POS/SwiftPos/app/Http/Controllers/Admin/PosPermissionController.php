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

class PosPermissionController extends Controller
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

       return view('swiftpos::layouts.permission');
    //    return redirect()->route('stores.index')
    }

    

    
}
