<?php
namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $connection = 'tenant'; // ensure it's pointing to tenant DB
    protected $fillable = [
        'name',
    ];
    public function users()
    {
        return $this->hasMany(TenantUser::class, 'role_id');
    }

    public function permissions()
    {
        // Explicitly tell Laravel the pivot table name
        return $this->belongsToMany(Permission::class, 'permission_roles', 'role_id', 'permission_id')
                    ->withPivot('menu_id');
    }
}
