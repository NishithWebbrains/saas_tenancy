<?php

namespace App\Services;

use App\Models\Store;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Tenant\TenantDetail;
use App\Models\Tenant\TenantUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class CreateStoreAndTenantService
{
    /**
     * @param array{name:string,slug:string,owner_user_id:int,pos_type:string} $payload
     * @return Store
     */
    public function handle(array $payload): Store
    {
        // Create the tenant (in central tenants table)
        $tenant = Tenant::create([
            'id' => $payload['id'],
            'data' => [
                'name'     => $payload['name'],
                'database' => "tenant_{$payload['id']}",
            ],
        ]);
        $tenant->run(function () use ($payload, $tenant) {
            // Store tenant details
            TenantDetail::create([
                'tenant_id' => $tenant->id,
                'name'      => $payload['name'],
                'slug'      => $payload['slug'],
                'pos_type'  => $payload['pos_type'],
            ]);
        // });
        // $tenant->run(function () use ($payload, $tenant) {
            // Store tenant details
            TenantUser::create([
                'name' => 'Store1 Manager',
                'email' => 'store1@manager.com',
                'password' => Hash::make('password'),
                'pos_type' => $payload['pos_type'],
                'roles' => ['manager'],
            ]);

            TenantUser::create([
                'name' => 'store1 Staff',
                'email' => 'store1@staff.com',
                'password' => Hash::make('password'),
                'pos_type' => $payload['pos_type'],
                'roles' => ['staff'],
            ]);
        });
        
        // Central "Store" record
        $store = Store::create([
            'name'          => $payload['name'],
            'slug'          => $payload['slug'],
            'tenant_id'     => $payload['id'],
            'owner_user_id' => $payload['owner_user_id'],
            'pos_type'      => $payload['pos_type'],
        ]);

        // Link owner user to tenant (many-to-many)
        $owner = User::findOrFail($payload['owner_user_id']);
        $owner->tenants()->syncWithoutDetaching([$tenant->id]);

        // Run tenant migrations (base + pos-specific)
        $tenant->run(function () use ($payload) {
            Artisan::call('migrate', [
                '--path'  => 'database/migrations/tenant',
                '--force' => true,
            ]);
            $posMigrationPath = "database/migrations/tenant/{$payload['pos_type']}";
            if (is_dir(base_path($posMigrationPath))) {
                Artisan::call('migrate', [
                    '--path'  => $posMigrationPath,
                    '--force' => true,
                ]);
            }
        });

        return $store;
    }
}
