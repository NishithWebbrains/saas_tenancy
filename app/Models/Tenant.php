<?php

namespace App\Models;

use App\Models\Tenant\TenantDetail;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Concerns\HasDatabase;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_user', 'tenant_id', 'user_id');
    }
    public function storeadmin()
    {
        return $this->belongsTo(User::class, 'storeadmin_id');
    }
    public function tenantDetails()
    {
        // One-to-one relation, assuming tenant_id in tenant_details matches tenant id
        return $this->hasOne(TenantDetail::class, 'tenant_id', 'id');
    }
}
