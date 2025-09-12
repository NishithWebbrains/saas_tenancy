<?php
// app/Models/TenantUser.php

namespace App\Models\Tenant;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    
    public function hasRole($roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }
}
