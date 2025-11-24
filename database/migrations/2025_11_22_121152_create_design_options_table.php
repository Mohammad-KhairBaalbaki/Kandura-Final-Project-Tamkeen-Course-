<?php

use App\Enums\MeasurementTypeEnum;
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
        Schema::create('design_options', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->enum('type',[MeasurementTypeEnum::Color,MeasurementTypeEnum::Sleeve,MeasurementTypeEnum::Dome,MeasurementTypeEnum::Fabric]);
            $table->unique(['name','type']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_options');
    }
};
