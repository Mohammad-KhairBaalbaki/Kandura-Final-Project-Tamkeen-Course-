<?php

namespace App\Services\Web;

use App\Enums\StatusEnum;
use App\Models\Design;
use App\Models\Measurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DesignService
{
    public function index(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $query = Design::with(['images', 'designOptions', 'measurements', 'user.image'])->where(function ($q) {
                $q->where('status', StatusEnum::ACTIVE);
                $q->whereHas('user', function ($query) {
                    $query->where('is_active', true);
                });

            });;

            if ($request->filled('name')) {
                $name = $request->name;
                $query->where('name->'.config('app.locale'), 'like', "%{$name}%");
            }

            if ($request->filled('description')) {
                $description = $request->description;
                $query->where('description->'.config('app.locale'), 'like', "%{$description}%");
            }

            if ($request->filled('user_name')) {
                $userName = $request->user_name;
                $query->whereHas('user', function ($q) use ($userName) {
                    $q->where('users.name', 'like', "%{$userName}%");
                });
            }

            if ($request->filled('measurements')) {
                $measurements = (array) $request->measurements;
                $query->whereHas('measurements', function ($q) use ($measurements) {
                    $q->whereIn('measurements.id', $measurements);
                });
            }

            if ($request->filled('min_price')) {
                $query->where('price', '>=', $request->min_price);
            }

            if ($request->filled('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }

            if ($request->filled('design_options_name')) {
                $designOptionsName = $request->design_options_name;
                $query->whereHas('designOptions', function ($q) use ($designOptionsName) {
                    $q->where('design_options.name->'.config('app.locale'), 'like', "%{$designOptionsName}%");
                });
            }

            if ($request->filled('design_options_color')) {
                $color = $request->design_options_color;
                $query->whereHas('designOptions', function ($q) use ($color) {
                    $q->where('design_options.type', 'color')
                        ->where('design_options.name->'.config('app.locale'), 'like', "%{$color}%");
                });
            }

            if ($request->filled('design_options_fabric')) {
                $fabric = $request->design_options_fabric;
                $query->whereHas('designOptions', function ($q) use ($fabric) {
                    $q->where('design_options.type', 'fabric')
                        ->where('design_options.name->'.config('app.locale'), 'like', "%{$fabric}%");
                });
            }

            if ($request->filled('design_options_dome')) {
                $dome = $request->design_options_dome;
                $query->whereHas('designOptions', function ($q) use ($dome) {
                    $q->where('design_options.type', 'dome')
                        ->where('design_options.name->'.config('app.locale'), 'like', "%{$dome}%");
                });
            }

            if ($request->filled('design_options_sleeve')) {
                $sleeve = $request->design_options_sleeve;
                $query->whereHas('designOptions', function ($q) use ($sleeve) {
                    $q->where('design_options.type', 'sleeve')
                        ->where('design_options.name->'.config('app.locale'), 'like', "%{$sleeve}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $designs = $query->latest()->paginate(15)->withQueryString();

            $measurements = Measurement::orderBy('size')->get();

            return [
                'designs' => $designs,
                'measurements' => $measurements,
            ];
        });
    }

    public function show(Design $design)
    {
        return DB::transaction(function () use ($design) {
            $salesCount = $design->itemsOrder()->sum('quantity');
            $design->load(['images', 'designOptions', 'measurements', 'user.image']);
            $design->sales_count = $salesCount;

            return $design;
        });
    }

    public function updateStatus(array $data, Design $design)
    {
        return DB::transaction(function () use ($data, $design) {


            $design->update([
                'status' => $data['status'],
            ]);

            return $design;
        });
    }
}
