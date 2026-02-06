<?php

use App\Enums\PaymentMethodEnum;
use App\Enums\StatusEnum;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('num')->nullable()->unique();
            $table->foreignId('user_id')->constrained('users')->noActionOnDelete();
            $table->enum('method', [PaymentMethodEnum::STRIPE, PaymentMethodEnum::AFTER_DELIVERY, PaymentMethodEnum::WALLET]);
            $table->enum('status', [StatusEnum::PENDING, StatusEnum::CANCELLED, StatusEnum::CONFIRMED, StatusEnum::FAILED])->default(StatusEnum::PENDING);
            $table->float('amount');
            $table->enum('type', ['pay', 'charge'])->default('pay');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
