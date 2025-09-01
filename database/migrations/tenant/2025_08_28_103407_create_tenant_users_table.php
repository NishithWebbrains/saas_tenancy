<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenant_users', function (Blueprint $table) {
            $table->id();
            
            // Reference to central users table (global user account)
            $table->unsignedBigInteger('user_id');
            
            // Role inside this tenant (e.g. storeadmin, staff, etc.)
            $table->string('role')->default('staff');
            
            $table->timestamps();

            // Constraints
            $table->unique(['user_id']); // prevent duplicate assignments in same tenant
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_users');
    }
};
