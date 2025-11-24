<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDesignRequest;
use App\Http\Requests\UpdateDesignRequest;
use App\Http\Resources\DesignResource;
use App\Models\Design;
use App\Services\DesignService;
use Illuminate\Http\Request;

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
        $designs = $this->designService->myDesigns();
        if (!$designs) {
            return $this->success(null, "UnAuthorized", 401);
        }
        return $this->success(DesignResource::collection($designs), "Designs Fetched Successfully .", 200);
    }

    //othersDesigns
    public function index(Request $request)
    {
        $designs = $this->designService->index($request->all());
        if (!$designs) {
            return $this->success(null, "UnAuthorized", 401);
        }
        return $this->success(DesignResource::collection($designs), "Designs Fetched Successfully .", 200);
    }
    public function store(StoreDesignRequest $request)
    {
        $design = $this->designService->store($request->validated());
        if (!$design) {
            return $this->success(null, "UnAuthorized", 401);
        }
        return $this->success(DesignResource::make($design), "Design Created Successfully .", 201);
    }
    public function update(UpdateDesignRequest $request, Design $design)
    {
        $design = $this->designService->update($request->validated(), $design);
        if (!$design) {
            return $this->success(null, "UnAuthorized", 401);
        }
        return $this->success(DesignResource::make($design), "Design Updated Successfully .", 200);
    }
    public function destroy(Design $design)
    {
        $design = $this->designService->destroy($design);
        if (!$design) {
            return $this->success(null, "UnAuthorized", 401);
        }
        return $this->success(null, "Design Deleted Successfully .", 200);
    }
}
