<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreDesignOptionRequest;
use App\Http\Requests\UpdateDesignOptionRequest;
use App\Http\Resources\DesignOptionResource;
use App\Models\DesignOption;
use App\Services\Global\DesignOptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DesignOptionController extends Controller
{
    //

    protected $designOptionService;
    public function __construct(DesignOptionService $designOptionService)
    {
        $this->designOptionService = $designOptionService;
    }
    public function index()
    {
        //
        try {
            $designOptions = $this->designOptionService->index();
            return $this->success(DesignOptionResource::collection($designOptions), "Design Options Fetched Successfully .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function store(StoreDesignOptionRequest $request)
    {
        //
        try {
            $designOption = $this->designOptionService->store($request->validated());
            if (!$designOption) {
                return $this->success(null, "UnAuthorized", 401);
            }
            return $this->success(DesignOptionResource::make($designOption), "Design Option Created Successfully .", 201);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function update(UpdateDesignOptionRequest $request, DesignOption $designOption)
    {
        //
        try {
            $designOption = $this->designOptionService->update($request->validated(), $designOption);
            if (!$designOption) {
                return $this->success(null, "UnAuthorized", 401);
            }
            return $this->success(DesignOptionResource::make($designOption), "Design Option Updated Successfully .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function destroy(DesignOption $designOption)
    {
        //
        try {
            $designOption = $this->designOptionService->delete($designOption);
            if (!$designOption) {
                return $this->success(null, "UnAuthorized", 401);
            }
            return $this->success(null, "Design Option Deleted Successfully .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }
}


