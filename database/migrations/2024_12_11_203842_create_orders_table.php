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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->bigInteger('tiktok_order_id')->nullable();
            $table->bigInteger('tracking_number')->nullable();
            $table->decimal('original_shipping_fee', 8, 2)->nullable();
            $table->decimal('original_total_product_price', 10, 2)->nullable();
            $table->decimal('seller_discount', 3, 2)->nullable();
            $table->decimal('shipping_fee', 8, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->string('order_status')->nullable();
            $table->timestamp('tiktok_create_date')->nullable();
            $table->timestamps();
            $table->decimal('net_revenue', 10, 2)->nullable();
            $table->decimal('base_cost', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
