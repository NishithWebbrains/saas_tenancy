<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantUser extends Model
{
    protected $table = 'tenant_users'; // the table inside tenant DB
    protected $fillable = ['user_id', 'role'];

    public function user()
    {
        // Points back to central User
        return $this->setConnection('central')
                    ->belongsTo(User::class, 'user_id');
    }
}
