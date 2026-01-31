<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDesignOptionRequest;
use App\Http\Requests\UpdateDesignOptionRequest;
use App\Models\DesignOption;
use App\Services\Web\DesignOptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DesignOptionController extends Controller
{
    protected $designOptionService;

    public function __construct(DesignOptionService $designOptionService)
    {
        $this->designOptionService = $designOptionService;
    }

    public function index(Request $request)
    {
        try {
            $designOptions = $this->designOptionService->index($request);
            return view('design_options.index', compact('designOptions'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function create()
    {
        try {
            $this->designOptionService->create();
            return view('design_options.create');
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function edit(DesignOption $designOption)
    {
        try {
            $designOption = $this->designOptionService->edit($designOption);
            return view('design_options.edit', compact('designOption'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function store(StoreDesignOptionRequest $request)
    {
        try {
            $designOption = $this->designOptionService->store($request);
            if (!$designOption) {
                return back()
                    ->withInput()
                    ->withErrors(['service' => __('design_options.not_authorized_create')]);
            }

            return redirect()->route('design_options.index');
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function update(UpdateDesignOptionRequest $request, DesignOption $designOption)
    {
        try {
            $updated = $this->designOptionService->update($request, $designOption);
            if (!$updated) {
                return back()
                    ->withInput()
                    ->withErrors(['service' => __('design_options.not_authorized_update')]);
            }

            return redirect()->route('design_options.index');
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function updateStatus(Request $request, DesignOption $designOption)
    {
        try {
            $updated = $this->designOptionService->updateStatus($request, $designOption);
            if (is_array($updated) && ($updated['error'] ?? null) === 'not_authorized') {
                return back()->withErrors(['status' => __('design_options.not_authorized_update_status')]);
            }

            return back();
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }
}
