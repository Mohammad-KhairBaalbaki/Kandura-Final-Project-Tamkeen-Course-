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
        //  'invoice_num',
        // 'order_id',
        // 'total',
        // 'pdf_url'
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('num');
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->float('total')->nullable();
            $table->string('pdf_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
