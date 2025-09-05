<?php
// app/Models/TenantUser.php

namespace App\Models\Tenant;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class TenantUser extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $table = 'tenant_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'pos_type',
        'roles',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'roles' => 'array',
        'password' => 'hashed',
    ];

    public function hasRole($role)
    {
        $roles = $this->roles ?? [];
        return in_array($role, $roles);
    }
}
