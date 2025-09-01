<?php
// database/migrations/xxxx_xx_xx_create_tenant_user_table.php (central)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('tenant_user', function (Blueprint $table) {
        $table->string('tenant_id');
        $table->unsignedBigInteger('user_id');
        $table->primary(['tenant_id', 'user_id']);
        $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        $table->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('tenant_user');
  }
};
