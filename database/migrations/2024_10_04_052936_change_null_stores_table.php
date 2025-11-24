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
        Schema::table('stores', function (Blueprint $table) {
            $table->bigInteger('user_id')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->integer('type')->nullable()->change();
            $table->integer('status')->nullable()->change();
            $table->string('timezone')->nullable()->change();
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
