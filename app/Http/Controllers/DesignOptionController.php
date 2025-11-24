<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDesignOptionRequest;
use App\Http\Requests\UpdateDesignOptionRequest;
use App\Http\Resources\DesignOptionResource;
use App\Models\DesignOption;
use App\Services\DesignOptionService;
use Illuminate\Http\Request;

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
        $designOptions = $this->designOptionService->index();
        return $this->success(DesignOptionResource::collection($designOptions), "Design Options Fetched Successfully .", 200);
    }

    public function store(StoreDesignOptionRequest $request)
    {
        //
        $designOption = $this->designOptionService->store($request->validated());
        if(!$designOption)
        {
            return $this->success(null,"UnAuthorized",401);
        }
        return $this->success(DesignOptionResource::make($designOption), "Design Option Created Successfully .", 201);
    }

    public function update(UpdateDesignOptionRequest $request, DesignOption $designOption)
    {
        //
        $designOption = $this->designOptionService->update($request->validated(),$designOption);
        if(!$designOption)
        {
            return $this->success(null,"UnAuthorized",401);
        }
        return $this->success(DesignOptionResource::make($designOption), "Design Option Updated Successfully .", 200);
    }

    public function destroy(DesignOption $designOption)
    {
        //
        $designOption = $this->designOptionService->delete($designOption);
        if(!$designOption)
        {
            return $this->success(null,"UnAuthorized",401);
        }
        return $this->success(null, "Design Option Deleted Successfully .", 200);
    }
}
