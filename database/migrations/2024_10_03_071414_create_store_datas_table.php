<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->text('data')->nullable();
            $table->string('remote_id')->nullable();
            $table->timestamps();
            $table->index('store_id');
            $table->index('product_id');
            $table->index('remote_id');
            $table->index('data');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_datas');
    }
};
