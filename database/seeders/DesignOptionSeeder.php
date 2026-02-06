<?php

namespace Database\Seeders;

use App\Enums\MeasurementTypeEnum;
use App\Models\DesignOption;
use Illuminate\Database\Seeder;

class DesignOptionSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            MeasurementTypeEnum::Color => [
                ['en' => 'White', 'ar' => 'أبيض'],
                ['en' => 'Black', 'ar' => 'أسود'],
                ['en' => 'Navy', 'ar' => 'كحلي'],
            ],
            MeasurementTypeEnum::Dome => [
                ['en' => 'Classic', 'ar' => 'كلاسيك'],
                ['en' => 'Modern', 'ar' => 'مودرن'],
                ['en' => 'Slim', 'ar' => 'سليم'],
            ],
            MeasurementTypeEnum::Fabric => [
                ['en' => 'Cotton', 'ar' => 'قطن'],
                ['en' => 'Linen', 'ar' => 'كتان'],
                ['en' => 'Silk', 'ar' => 'حرير'],
            ],
            MeasurementTypeEnum::Sleeve => [
                ['en' => 'Long', 'ar' => 'طويل'],
                ['en' => 'Short', 'ar' => 'قصير'],
                ['en' => 'Classic', 'ar' => 'كلاسيك'],
            ],
        ];

        foreach ($options as $type => $names) {
            foreach ($names as $idx => $name) {
                $option = DesignOption::firstOrCreate(
                    [
                        'type' => $type,
                        'name->en' => $name['en'],
                        'name->ar' => $name['ar'],
                    ],
                    [
                        'name' => $name,
                        'is_active' => true,
                    ]
                );

                // Make one option per type inactive for variety
                if ($idx === 2) {
                    $option->update(['is_active' => false]);
                }
            }
        }

        // Soft delete one option to cover deleted state
        $toDelete = DesignOption::where('type', MeasurementTypeEnum::Fabric)->first();
        if ($toDelete) {
            $toDelete->delete();
        }
    }
}
