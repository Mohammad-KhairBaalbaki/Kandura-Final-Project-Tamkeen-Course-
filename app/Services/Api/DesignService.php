<?php

namespace App\Services\Api;

use App\Enums\StatusEnum;
use App\Models\Design;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DesignService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function myDesigns()
    {
        return DB::transaction(function () {

            $user = User::findOrFail(Auth::id());
            return $user->designs()->with('images', 'designOptions', 'measurements')->get();
        });
    }

    public function index(array $params = [])
    {

        //search name description userName
        //filter size priceRange design option creator
        return DB::transaction(function () use ($params) {

            $query = Design::with('images', 'designOptions', 'measurements')->where(function ($q) {
                $q->where('status', StatusEnum::ACTIVE);
                $q->whereHas('user', function ($query) {
                    $query->where('is_active', true);
                });

            });
            if (Auth::check()) {
                $query = Design::whereNot('user_id', Auth::id());
            }

            if (isset($params['name'])) {
                $name = $params['name'];
                $query->where(function ($q) use ($name) {
                    $q->where('name->' . config('app.locale'), 'like', "%{$name}%");

                });
            }
            if (isset($params['description'])) {
                $description = $params['description'];
                $query->where(function ($q) use ($description) {
                    $q->where('description->' . config('app.locale'), 'like', "%{$description}%");
                });
            }

            if (isset($params['user_name'])) {
                $userName = $params['user_name'];

                $query->whereHas('user', function ($q) use ($userName) {
                    $q->where('users.name', 'like', "%{$userName}%");
                });
            }

            if (isset($params['measurements']) && is_array($params['measurements'])) {
                $query->whereHas('measurements', function ($q) use ($params) {
                    $q->whereIn('measurements.id', $params['measurements']);
                });
            }

            if (isset($params['min_price'])) {
                $minPrice = $params['min_price'];
                $query->where('price', '>=', $minPrice);
            }

            if (isset($params['max_price'])) {
                $maxPrice = $params['max_price'];
                $query->where('price', '<=', $maxPrice);
            }


            if (isset($params['design_options_name'])) {
                $query->whereHas('designOptions', function ($q) use ($params) {
                    $q->where('design_options.name->' . config('app.locale'), 'like', $params['design_options_name']);
                });
            }
            if (isset($params['design_options_type']) && is_array($params['design_options_type'])) {
                $query->whereHas('designOptions', function ($q) use ($params) {
                    $q->whereIn('design_options.type', $params['design_options_type']);
                });
            }
            $perPage = $params['per_page'] ?? 5;
            return $query->paginate($perPage);
        });
    }
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {


            $user = Auth::user();
            $userArray = ['user_id' => $user->id];
            $data = array_merge($data, $userArray);
            $design = Design::create($data);
            if (isset($data['images'])) {
                foreach ($data['images'] as $idx => $file) {
                    $path = 'design/' . $design->id . '/' . $idx;
                    $design->images()->create([
                        'url' => ImageService::uploadImage($file, $path),
                    ]);

                }
            }
            if (!empty($data['design_options']) && is_array($data['design_options'])) {
                $design->designOptions()->sync($data['design_options']);
            }

            if (!empty($data['measurements']) && is_array($data['measurements'])) {
                $design->measurements()->sync($data['measurements']);
            }
            return $design->load('images', 'designOptions', 'measurements');
        });
    }
    public function update(array $data, Design $design)
    {
        return DB::transaction(function () use ($data, $design) {


            $design->update($data);
            if (isset($data['images'])) {
                foreach ($data['images'] as $idx => $file) {
                    $path = 'designs/' . $design->id . '/' . $idx;
                    $design->images()->update([
                        'url' => ImageService::uploadImage($file, $path),
                    ]);

                }
            }
            if (!empty($data['design_options']) && is_array($data['design_options'])) {
                $design->designOptions()->sync($data['design_options']);
            }

            if (!empty($data['measurements']) && is_array($data['measurements'])) {
                $design->measurements()->sync($data['measurements']);
            }
            $design = Design::findOrFail($design->id);
            return $design;
        });
    }
    public function destroy(Design $design)
    {
        return DB::transaction(function () use ($design) {

            if ($design->user_id !== Auth::id())
                return false;
            $design->delete();
            return true;
        });
    }
}

