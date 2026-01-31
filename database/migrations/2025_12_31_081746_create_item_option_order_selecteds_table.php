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
        Schema::create('item_option_order_selecteds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_order_id')->constrained('item_orders')->cascadeOnDelete();
            $table->foreignId('design_option_id')->constrained('design_options')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_option_order_selecteds');
    }
};
