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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->nullable();
            $table->string('payout_id')->nullable();
            $table->decimal('payout_amout',8,2)->nullable();
            $table->decimal('settlement_amount',8,2)->nullable();
            $table->decimal('amount_before_exchange',8,2)->nullable();
            $table->decimal('reserve_amount',8,2)->nullable();
            $table->datetime('date')->nullable();
            $table->datetime('date_complete')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
