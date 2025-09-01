<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $storeadmin = Role::firstOrCreate(['name' => 'storeadmin']);
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $manager = Role::firstOrCreate(['name' => 'manager']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@saas.test'],
            ['name' => 'Super Admin', 'password' => Hash::make('password')]
        );

        $admin->assignRole($superadmin);
    }
}
