<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function profile()
    {
        $user = $this->userService->profile();
        return $this->success(UserResource::make($user), "User Retrieved Successfully .", 200);
    }


    public function update(UpdateUserRequest $request,User $user)
    {
        $this->userService->update($request->validated(),$user);
        $user = User::findOrFail(Auth::id());
        return $this->success(UserResource::make($user),"User Updated Successfully .",200);
    }


    
}
