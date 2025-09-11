<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Insert permissions
        DB::table('permissions')->insertOrIgnore([
            
            ['name' => 'View',   'slug'=>'view',  'created_at'=>now(),'updated_at'=>now()],
            ['name' => 'Authorize',   'slug'=>'authorize',  'created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
