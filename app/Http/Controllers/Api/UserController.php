<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserImageRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Api\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        try {
            $user = $this->userService->profile();

            return $this->success(UserResource::make($user), 'User Retrieved Successfully .', 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $this->userService->update($request->validated(), $user);
            $user = User::findOrFail(Auth::id());

            return $this->success(UserResource::make($user), 'User Updated Successfully .', 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function updatePhoto(UpdateUserImageRequest $request)
    {
        try {
            $user = $this->userService->updatePhoto($request->validated());

            return $this->success(UserResource::make($user), 'Profile Photo Updated Successfully .', 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, $e->getMessage(), 422);
        }
    }
}
