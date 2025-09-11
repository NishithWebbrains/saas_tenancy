<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');        // e.g. "Create"
            $table->string('slug')->nullable(); // optional
            $table->timestamps();
        });
        DB::table('permissions')->insert([
            ['name' => 'View',   'slug' => 'view',   'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
