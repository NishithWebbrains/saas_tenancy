<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreIntegrationsTable extends Migration
{
    public function up()
    {
        Schema::create('store_integrations', function (Blueprint $table) {
            $table->id();
            $table->string('pos_type'); // shopfront, swiftpos, bigcommerce
            $table->json('pos_credentials')->nullable();
            $table->string('zkong_api_key')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('store_integrations');
    }
}
