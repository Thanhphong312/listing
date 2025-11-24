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
        Schema::table('product_skus', function (Blueprint $table) {
            $table->bigInteger('product_id')->nullable()->change();
            $table->string('price')->nullable()->change();
            $table->json('sales_attributes')->nullable()->change();
            $table->boolean('seller_sku')->nullable()->change();
            $table->string('inventory')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
