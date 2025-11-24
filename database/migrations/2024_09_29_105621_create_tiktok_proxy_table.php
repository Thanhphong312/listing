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
        Schema::create('tiktok_proxy', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->unsignedInteger('seller_id');
            $table->string('note')->nullable();
            $table->string('status')->nullable();
            $table->string('status_class_c');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiktok_proxy');
    }
};
