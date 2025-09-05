<?php
// database/seeders/tenant/TenantUserSeeder.php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use App\Models\Tenant\TenantUser;
use Illuminate\Support\Facades\Hash;

class TenantUserSeeder extends Seeder
{
    public function run(): void
    {
        TenantUser::create([
            'name' => 'Store1 Manager',
            'email' => 'store1@manager.com',
            'password' => Hash::make('password'),
            'pos_type' => 'abspos',
            'roles' => ['manager'],
        ]);

        TenantUser::create([
            'name' => 'store1 Staff',
            'email' => 'store1@staff.com',
            'password' => Hash::make('password'),
            'pos_type' => 'abspos',
            'roles' => ['staff'],
        ]);
    }
}
