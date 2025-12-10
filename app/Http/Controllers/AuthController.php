<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function apiRegister(RegisterRequest $request)
    {
        $user = $this->authService->apiRegister($request->validated());
        return $this->success(LoginResource::make($user), "User Created Successfully .", 201);
    }
    public function apiLogin(LoginRequest $request)
    {
        $message = "User Logged In Successfully .";
        $user = $this->authService->apiLogin($request->validated());
        if (!$user) {
            $message = "Invalid Credntials";
            return $this->success(null, $message, 400);
        }
        return $this->success(LoginResource::make($user), $message, 200);
    }
    public function apiLogout()
    {

    }

    public function webRegister()
    {

    }

    public function webLogout()
    {
        $this->authService->webLogout();
        return redirect()->route('login');
    }

    public function showLoginForm(){
        return view('auth.login');
    }
}
