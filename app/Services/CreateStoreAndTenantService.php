<?php

namespace App\Services;

use App\Models\Store;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class CreateStoreAndTenantService
{
    /**
     * @param array{name:string,slug:string,owner_user_id:int,pos_type:string} $payload
     * @return Store
     */
    public function handle(array $payload): Store
    {
        // For path-based tenancy, use slug as tenant ID (clean URLs)
        $tenantId = $payload['slug'];

        // 1) Create the tenant (in central tenants table)
        /** @var \App\Models\Tenant $tenant */
        $tenant = Tenant::create([
            'id' => $tenantId,
            'data' => [
                'name'     => $payload['name'],
                'database' => "tenant_{$tenantId}",
            ],
        ]);

        // 2) Central "Store" record
        $store = Store::create([
            'name'          => $payload['name'],
            'slug'          => $payload['slug'],
            'tenant_id'     => $tenant->id,
            'owner_user_id' => $payload['owner_user_id'],
            'pos_type'      => $payload['pos_type'],
        ]);

        // 3) Link owner user to tenant (many-to-many)
        /** @var User $owner */
        $owner = User::findOrFail($payload['owner_user_id']);
        $owner->tenants()->syncWithoutDetaching([$tenant->id]);

        // 4) Run tenant migrations (base + pos-specific)
        $tenant->run(function () use ($payload) {
            // Run common tenant migrations
            Artisan::call('migrate', [
                '--path'  => 'database/migrations/tenant',
                '--force' => true,
            ]);

            // Run POS-specific migrations if available
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
