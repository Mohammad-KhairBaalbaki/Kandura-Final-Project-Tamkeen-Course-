<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    //
    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function webLogin(LoginRequest $request)
    {
        $user = $this->authService->webLogin($request->validated());
        if (!$user) {
            return back()->withErrors([
                'password' => 'Invalid credentials. Please check your email and password.',
            ]);
        }
        return redirect()->route('dashboard');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function webLogout()
    {
        $this->authService->webLogout();
        return redirect()->route('login');
    }
}
