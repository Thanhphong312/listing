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
        Schema::create('partner_apps', function (Blueprint $table) {
            $table->id();
            $table->string('app_name');
            $table->text('app_key');
            $table->text('app_secret');
            $table->string('auth_link');
            $table->string('proxy');
            $table->integer('seller_id');
            $table->string('status')->default('active');
            $table->string('webhook_domain')->nullable();
            $table->integer('count_shop_connect')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_apps');
    }
};
