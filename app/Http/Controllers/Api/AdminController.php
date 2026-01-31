<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Global\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    //

    protected $adminService;
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }
    //Add admin
    public function store(RegisterRequest $request)
    {
        try {
            $user = $this->adminService->storeAdmin($request->validated());
            if (!$user) {
                return $this->success(null, "UnAuthorized", 401);
            }
            return $this->success(UserResource::make($user), "User Created Successfully .", 201);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $user = $this->adminService->updateAdmin($request->validated(), $user);
            if (!$user) {
                return $this->success(null, "UnAuthorized", 401);
            }
            return $this->success(UserResource::make($user), "User Updated Successfully .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }
    public function destroy(User $user)
    {
        try {
            $user = $this->adminService->deleteAdmin($user);
            if (!$user) {
                return $this->success(null, "UnAuthorized", 401);
            }
            return $this->success(null, "User Deleted Successfully .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }
}


