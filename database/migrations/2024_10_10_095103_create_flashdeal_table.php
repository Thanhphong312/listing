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
        Schema::create('flashdeal', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->nullable();
            $table->string('promotion_name')->nullable();
            $table->string('activity_type')->nullable();
            $table->string('product_level')->nullable();
            $table->string('status_fld')->nullable();
            $table->string('begin_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('auto')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flashdeal');
    }
};
