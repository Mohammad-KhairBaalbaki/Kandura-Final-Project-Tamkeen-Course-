<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Design;
use App\Models\DesignOption;
use App\Models\Measurement;
use App\Models\User;
use Illuminate\Database\Seeder;

class DesignSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('user')->get();

        $measurements = Measurement::all();
        $options = DesignOption::whereNull('deleted_at')->get()->groupBy('type');

        $statuses = [StatusEnum::ACTIVE, StatusEnum::INACTIVE, StatusEnum::BLOCKED];
        $index = 1;

        foreach ($users as $user) {
            foreach ($statuses as $status) {
                $design = Design::create([
                    'user_id' => $user->id,
                    'name' => [
                        'en' => "Kandoura Design {$index}",
                        'ar' => "تصميم كندورة {$index}",
                    ],
                    'description' => [
                        'en' => "Sample design {$index} description",
                        'ar' => "وصف تجريبي للتصميم {$index}",
                    ],
                    'price' => 50 + ($index * 5),
                    'status' => $status,
                ]);

                // Attach one option per type
                $optionIds = collect();
                foreach ($options as $type => $group) {
                    $option = $group->first();
                    if ($option) {
                        $optionIds->push($option->id);
                    }
                }
                if ($optionIds->isNotEmpty()) {
                    $design->designOptions()->sync($optionIds->all());
                }

                // Attach measurements (2 sizes)
                $measurementIds = $measurements->pluck('id')->take(2)->all();
                if (! empty($measurementIds)) {
                    $design->measurements()->sync($measurementIds);
                }

                $index++;
            }
        }

        if (Design::onlyTrashed()->count() < 1) {
            $toDelete = Design::inRandomOrder()->first();
            if ($toDelete) {
                $toDelete->delete();
            }
        }
    }
}
