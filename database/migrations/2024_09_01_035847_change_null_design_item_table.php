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
        Schema::table('design_items', function (Blueprint $table) {
            $table->bigInteger('design_id')->nullable()->change();
            $table->bigInteger('category_id')->nullable()->change();
            $table->text('front_design')->nullable()->change();
            $table->text('back_design')->nullable()->change();
            $table->text('sleeve_left_design')->nullable()->change();
            $table->text('sleeve_right_design')->nullable()->change();
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
