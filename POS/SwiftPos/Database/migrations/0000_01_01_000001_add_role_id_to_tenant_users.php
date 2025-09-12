<?php 
// database/migrations/tenant/xxxx_xx_xx_xxxxxx_add_role_id_to_tenant_users.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tenant_users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->after('pos_type');
        });
    }

    public function down(): void
    {
        Schema::table('tenant_users', function (Blueprint $table) {
            if (Schema::hasColumn('tenant_users', 'role_id')) {
            $table->dropColumn('role_id');
            }
        });
    }
};
