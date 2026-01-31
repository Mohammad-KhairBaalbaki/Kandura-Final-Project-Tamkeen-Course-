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
        /* 'code',
        'validate_from',
        'validate_until',
        'is_percentage',
        'amount',
        'is_active',
        'general_limit',
        'usages'
        */
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->date('validate_from')->default(now());
            $table->date('validate_until')->default(now()->addDays(10));
            $table->boolean('is_percentage')->default(false);
            $table->float('amount');
            $table->boolean('is_active')->default(true);
            $table->float('order_limit_amount')->default(0);
            $table->integer('general_limit');
            $table->integer('usages')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
