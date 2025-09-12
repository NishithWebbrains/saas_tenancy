<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');       // e.g. Products, Orders, Promotions
            $table->string('slug')->unique(); // e.g. products, orders
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Optional: Seed some default modules
        DB::table('menus')->insert([
            ['name' => 'Dashboard',   'slug' => 'dashboard',   'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
