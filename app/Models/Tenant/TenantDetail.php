<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class TenantDetail extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'pos_type'
    ];
}
