<?php
namespace App\Services;

use App\Models\Store;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Tenant\TenantDetail;
use App\Models\Tenant\TenantUser;
use App\Models\Tenant\Role;
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

       
        // Run tenant migrations (base + pos-specific)
        $tenant->run(function () use ($payload) {
            // Map pos_type to folder
            $map = [
                'swiftpos'    => 'SwiftPos',
                'abspos'      => 'AbsPos',
                'shopfrontpos'=> 'ShopfrontPos',
            ];
        
            $foldername = $map[$payload['pos_type']] ?? null;
        
            if ($foldername) {
                $posMigrationPath = "POS/{$foldername}/Database/migrations";
        
                if (is_dir(base_path($posMigrationPath))) {
                    Artisan::call('migrate', [
                        '--path'  => $posMigrationPath,
                        '--force' => true,
                    ]);
                }
                
                // $posMigrationPath = "POS/{$foldername}/database/migrations/{$payload['pos_type']}";
                // if (is_dir(base_path($posMigrationPath))) {
                //     Artisan::call('migrate', [
                //         '--path'  => $posMigrationPath,
                //         '--force' => true,
                //     ]);
                // }
            }
        });
        
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
            $roleManager = Role::where('name', 'manager')->first();
            $roleStaff   = Role::where('name', 'staff')->first();

            $manager = TenantUser::create([
                'name' => 'Store1 Manager',
                'email' => 'store1@manager.com',
                'password' => Hash::make('password'),
                'pos_type' => $payload['pos_type'],
                'role_id'  => $roleManager->id,
            ]);
            
            $staff = TenantUser::create([
                'name' => 'store1 Staff',
                'email' => 'store1@staff.com',
                'password' => Hash::make('password'),
                'pos_type' => $payload['pos_type'],
                'role_id'  => $roleStaff->id,
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

        

        return $store;
    }
}
