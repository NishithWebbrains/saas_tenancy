<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('clientid')->nullable();
            $table->string('client_password')->nullable();
            $table->string('store_id')->nullable();
            $table->string('external_storeid')->nullable();
            $table->string('shopfrontpos_vendor_identifier')->nullable();
            $table->string('swiftpos_vendor_identifier')->nullable();
            $table->string('abspos_vendor_identifier')->nullable();
            $table->string('token')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn([
                'clientid',
                'client_password',
                'store_id',
                'external_storeid',
                'shopfrontpos_vendor_identifier',
                'swiftpos_vendor_identifier',
                'abspos_vendor_identifier',
                'token',
            ]);
        });
    }
    
};
