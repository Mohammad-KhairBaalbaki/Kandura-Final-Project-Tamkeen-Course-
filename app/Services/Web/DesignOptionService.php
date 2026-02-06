<?php

namespace App\Services\Web;

use App\Http\Requests\StoreDesignOptionRequest;
use App\Http\Requests\UpdateDesignOptionRequest;
use App\Models\DesignOption;
use App\Services\Api\DesignOptionService as CoreDesignOptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DesignOptionService
{
    protected $designOptionService;

    public function __construct(CoreDesignOptionService $designOptionService)
    {
        $this->designOptionService = $designOptionService;
    }

    public function index(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $query = DesignOption::query();

            if ($request->filled('name')) {
                $name = $request->name;
                $query->where(function ($q) use ($name) {
                    $q->where('name->' . config('app.locale'), 'like', "%{$name}%");
                });
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('is_active')) {
                $query->where('is_active', $request->is_active);
            }

            return $query->latest()->paginate(15)->withQueryString();
        });
    }

    public function trashed(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $query = DesignOption::onlyTrashed();

            if ($request->filled('name')) {
                $name = $request->name;
                $query->where(function ($q) use ($name) {
                    $q->where('name->' . config('app.locale'), 'like', "%{$name}%");
                });
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            return $query->latest('deleted_at')->paginate(15)->withQueryString();
        });
    }

    public function create()
    {
        return DB::transaction(function () {
            return true;
        });
    }

    public function edit(DesignOption $designOption)
    {
        return DB::transaction(function () use ($designOption) {
            return $designOption;
        });
    }

    public function store(StoreDesignOptionRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();
            if ($request->has('is_active')) {
                $data['is_active'] = $request->boolean('is_active');
            }
            $designOption = DesignOption::create($data);

            return $designOption;
        });
    }

    public function update(UpdateDesignOptionRequest $request, DesignOption $designOption)
    {
        return DB::transaction(function () use ($request, $designOption) {
            $data = $request->validated();
            if ($request->has('is_active')) {
                $data['is_active'] = $request->boolean('is_active');
            }

            return $this->designOptionService->update($data, $designOption);
        });
    }

    public function updateStatus(array $data, DesignOption $designOption)
    {
        return DB::transaction(function () use ($data, $designOption) {
            $user = Auth::user();
            if (!$user || !$user->hasPermissionTo('edit-design-option')) {
                return [
                    'error' => 'not_authorized',
                ];
            }



            $updated = $this->designOptionService->update(
                ['is_active' => (bool) $data['is_active']],
                $designOption
            );

            if (!$updated) {
                return [
                    'error' => 'not_authorized',
                ];
            }

            return true;
        });
    }

    public function destroy(DesignOption $designOption): bool
    {
        return DB::transaction(function () use ($designOption) {
            return (bool) $designOption->delete();
        });
    }

    public function restore(int $designOptionId): bool
    {
        return DB::transaction(function () use ($designOptionId) {
            $designOption = DesignOption::withTrashed()->find($designOptionId);
            if (!$designOption) {
                return false;
            }

            return (bool) $designOption->restore();
        });
    }
}

