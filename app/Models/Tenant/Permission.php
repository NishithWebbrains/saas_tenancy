<?php
namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $connection = 'tenant';
    protected $table = 'permissions';
    protected $fillable = ['name','slug'];
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_roles', 'permission_id', 'role_id');
      
    }
}
