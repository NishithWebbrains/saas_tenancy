<?php
namespace POS\SwiftPos\App\Http\Controllers\Admin;

use POS\SwiftPos\App\Http\Controllers\Controller;
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

        return view('swiftpos::layouts.permission', compact('role', 'menus', 'permissions', 'assignedPermissions'));
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
        return view('swiftpos::roles.permission', array_merge($data, ['tenantId' => $tenantId]));
    }

    // Save the permissions (expects an array of values)
    // Accepts permissions as array of strings "permissionId,menuId" OR structured array
    public function savePermissions(Request $request, $tenantId, $roleId)
    {
        $request->validate([
            'permissions' => 'nullable|array'
        ]);

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return response()->json(['message' => 'Tenant not found.'], 404);
        }

        $permissions = $request->input('permissions', []);

        try {
            $tenant->run(function () use ($permissions, $roleId) {
                // Remove old permissions for role
                DB::table('permission_roles')->where('role_id', $roleId)->delete();

                $toInsert = [];
                foreach ($permissions as $p) {
                    // if value is "permissionId,menuId"
                    if (is_string($p) && strpos($p, ',') !== false) {
                        [$permissionId, $menuId] = explode(',', $p);
                        $toInsert[] = [
                            'role_id' => $roleId,
                            'menu_id' => (int) $menuId,
                            'permission_id' => (int) $permissionId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    } elseif (is_array($p) && isset($p['permission_id']) && isset($p['menu_id'])) {
                        $toInsert[] = [
                            'role_id' => $roleId,
                            'menu_id' => (int) $p['menu_id'],
                            'permission_id' => (int) $p['permission_id'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                if (!empty($toInsert)) {
                    DB::table('permission_roles')->insert($toInsert);
                }
            });

            return response()->json(['message' => 'Permissions saved successfully.']);
        } catch (\Throwable $e) {
            \Log::error('Error saving permissions: '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return response()->json(['message' => 'Failed to save permissions.'], 500);
        }
    }
}
