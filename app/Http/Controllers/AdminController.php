<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //

    protected $adminService;
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }
    //Add admin
    public function store(RegisterRequest $request){
        $user = $this->adminService->storeAdmin($request->validated());
        if(!$user){
            return $this->success(null,"UnAuthorized",401);
        }
        return $this->success(UserResource::make($user),"User Created Successfully .",201);
    }

    public function update(UpdateUserRequest $request,User $user){
        $user = $this->adminService->updateAdmin($request->validated(),$user);
        if(!$user){
            return $this->success(null,"UnAuthorized",401);
        }
        return $this->success(UserResource::make($user),"User Updated Successfully .",200);
    }
    public function destroy(User $user){
        $user = $this->adminService->deleteAdmin($user);
        if(!$user){
            return $this->success(null,"UnAuthorized",401);
        }
        return $this->success(null,"User Deleted Successfully .",200);
    }
}
