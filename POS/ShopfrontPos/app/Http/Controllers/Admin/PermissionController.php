<?php
namespace POS\ShopfrontPos\App\Http\Controllers\Admin;

use POS\ShopfrontPos\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\Permission;
use App\Models\Tenant\PermissionRole;
use App\Models\Tenant\Role; // optional if you have it
use App\Models\Tenant\Menu; // optional
use App\Models\Tenant; // Your central Tenant model - adjust namespace
use Stancl\Tenancy\Tenant as TenancyTenant; // adjust if you use different package

class PermissionController extends Controller
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
    public function view(Request $request)
    {
        $tenantId = $this->tenantId;
        $roleId = $request->input('role');

        $tenant = Tenant::findOrFail($tenantId);
        $role = null;
        $menus = [];
        $permissions = [];
        $assignedPermissions = [];

        $tenant->run(function () use ($roleId, &$role, &$menus, &$permissions, &$assignedPermissions) {
            $role = Role::findOrFail($roleId);
            $menus = Menu::all();
            $permissions = Permission::all();

            $assigned = Permissionrole::where('role_id', $roleId)->get();
            foreach ($assigned as $ap) {
                $assignedPermissions[$ap->menu_id][] = $ap->permission_id;
            }
        });

        return view('shopfrontpos::layouts.permission', compact('role', 'menus', 'permissions', 'assignedPermissions'));
    }


    // Show permission page (loads role, menus, permissions, existing assignment)
    public function getPermission($tenantId, $roleId)
    {
        $tenant = \App\Models\Tenant::find($tenantId); // adjust central Tenant model namespace
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }   

        $data = [];
        $tenant->run(function () use (&$data, $roleId) {
            $data['role'] = DB::table('roles')->where('id', $roleId)->first();
            $data['menus'] = DB::table('menus')->where('id', '!=', 1)->get(); // as your example
            $data['permissions'] = DB::table('permissions')->get();
            $data['permissionroles'] = DB::table('permission_roles')->where('role_id', $roleId)->get();
        });

        // Pass tenantId so blade can build routes
        return view('shopfrontpos::roles.permission', array_merge($data, ['tenantId' => $tenantId]));
    }

    // Save the permissions (expects an array of values)
    // Accepts permissions as array of strings "permissionId,menuId" OR structured array
    public function savepermissions(Request $request , $roleId)
    {
        \Log::info('savepermissions called', [
            'all_route_params' => request()->route()->parameters(),
            'role_param'   => $roleId ?? null,
        ]);
        $tenantId = request()->route('tenant')
        ?? request()->segment(1)
        ?? request()->input('tenant')
        ?? null;
        $tenant = Tenant::findOrFail($tenantId);
    
        $tenant->run(function () use ($request, $roleId) {
            $role = Role::findOrFail($roleId);
    
            // Get permissions array from request
            $permissionsInput = $request->input('permissions', []); 
            // This will be grouped by menu_id
    
            $permissionIds = [];
            foreach ($permissionsInput as $menuId => $perms) {
                foreach ($perms as $permId) {
                    $permissionIds[$permId] = ['menu_id' => $menuId]; // already actual permission IDs
                }
            }
    
            // Sync directly since we already have IDs
            $role->permissions()->sync($permissionIds);
        });
    
        return response()->json(['message' => 'Permissions saved successfully.']);
    }
    

}
