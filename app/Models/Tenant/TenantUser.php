<?php
// app/Models/TenantUser.php

namespace App\Models\Tenant;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class TenantUser extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $table = 'tenant_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'pos_type',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function canAccess($menuSlug, $permissionSlug)
    {
        $role = $this->role;
        if (!$role) {
            return false;
        }
    
        // Find the menu by slug
        $menuId = DB::table('menus')
            ->where('slug', $menuSlug)
            ->value('id');
    
        if (!$menuId) {
            return false; // menu not found
        }
    
        // Check if role has this permission for the menu
        return DB::table('permission_roles')
            ->join('permissions', 'permission_roles.permission_id', '=', 'permissions.id')
            ->where('permission_roles.role_id', $role->id)
            ->where('permission_roles.menu_id', $menuId)
            ->where('permissions.slug', $permissionSlug)
            ->exists();
    }
    
    public function permissions()
    {
        return $this->role
            ? $this->role->permissions()
            : collect();
    }
    public function hasRole($roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }
    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions()->contains('name', $permissionName);
    }
}
