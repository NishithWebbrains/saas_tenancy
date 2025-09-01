<?php
// app/Models/Store.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Models\Domain;

class Store extends Model
{
    protected $guarded = [];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }
    
    public function domain()
    {
        return $this->hasOne(Domain::class, 'tenant_id', 'tenant_id');
    }
}
