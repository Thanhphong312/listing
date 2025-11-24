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
        Schema::create('product_flashdeal', function (Blueprint $table) {
            $table->id();
            $table->string('flashdeal_id')->nullable();
            $table->string('product_id')->nullable();
            $table->string('quantity_limit')->nullable();
            $table->string('squantity_per_user')->nullable();
            $table->json('skus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_flashdeal');
    }
};
