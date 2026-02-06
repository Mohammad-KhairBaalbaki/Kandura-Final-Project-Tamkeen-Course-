<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\LoginResource;
use App\Services\Api\AuthService;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    //
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->authService->register($request->validated());

            return $this->success(LoginResource::make($user), 'User Created Successfully .', 201);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $message = 'User Logged In Successfully .';
            $user = $this->authService->login($request->validated());
            if (! $user) {
                $message = 'Invalid Credntials';

                return $this->success(null, $message, 400);
            }

            return $this->success(LoginResource::make($user), $message, 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }
}
