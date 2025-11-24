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
        Schema::create('design_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('design_id');
            $table->bigInteger('category_id');
            $table->text('front_design');
            $table->text('back_design');
            $table->text('sleeve_left_design');
            $table->text('sleeve_right_design');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_items');
    }
};
