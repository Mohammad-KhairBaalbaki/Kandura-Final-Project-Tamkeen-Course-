<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreDesignRequest;
use App\Http\Requests\UpdateDesignRequest;
use App\Http\Resources\DesignResource;
use App\Models\Design;
use App\Services\Api\DesignService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DesignController extends Controller
{
    //
    protected $designService;

    public function __construct(DesignService $designService)
    {
        $this->designService = $designService;
    }

    public function myDesigns()
    {
        try {
            $designs = $this->designService->myDesigns();
            if (!$designs) {
                return $this->success(null, "UnAuthorized", 401);
            }
            return $this->success(DesignResource::collection($designs), "Designs Fetched Successfully .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    //othersDesigns
    public function index(Request $request)
    {
        try {
            $designs = $this->designService->index($request->all());
            if (!$designs) {
                return $this->success(null, "UnAuthorized", 401);
            }
            return $this->success(DesignResource::collection($designs), "Designs Fetched Successfully .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }
    public function store(StoreDesignRequest $request)
    {
        try {
            $design = $this->designService->store($request->validated());
            if (!$design) {
                return $this->success(null, "UnAuthorized", 401);
            }
            return $this->success(DesignResource::make($design), "Design Created Successfully .", 201);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }
    public function update(UpdateDesignRequest $request, Design $design)
    {
        try {
            $design = $this->designService->update($request->validated(), $design);
            if (!$design) {
                return $this->success(null, "UnAuthorized", 401);
            }
            return $this->success(DesignResource::make($design), "Design Updated Successfully .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }
    public function destroy(Design $design)
    {
        try {
            $design = $this->designService->destroy($design);
            if (!$design) {
                return $this->success(null, "UnAuthorized", 401);
            }
            return $this->success(null, "Design Deleted Successfully .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }
}


