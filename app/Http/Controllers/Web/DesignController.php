<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDesignStatusRequest;
use App\Models\Design;
use App\Services\Web\DesignService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DesignController extends Controller
{
    protected $designService;

    public function __construct(DesignService $designService)
    {
        $this->designService = $designService;
    }

    public function index(Request $request)
    {
        try {
            $data = $this->designService->index($request);

            return view('designs.index', $data);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function show(Design $design)
    {
        try {
            $design = $this->designService->show($design);

            return view('designs.show', compact('design'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function updateStatus(UpdateDesignStatusRequest $request, Design $design)
    {
        try {
            $this->designService->updateStatus($request->validated(), $design);

            return redirect()->route('designs.index');
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }
}
