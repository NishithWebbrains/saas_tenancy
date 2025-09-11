<?php
namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    protected $table = 'permission_roles';
    protected $fillable = ['role_id','menu_id','permission_id'];
}
