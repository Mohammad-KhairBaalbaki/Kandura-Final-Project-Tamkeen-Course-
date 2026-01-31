<?php

namespace App\Services\Web;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthService
{

    public function __construct()
    {
    }

    public function login(array $data)
    {
        return DB::transaction(function () use ($data) {

            if (isset($data['email'])) {
                // Attempt login with email
                $credentials = ['email' => $data['email'], 'password' => $data['password']];
            } elseif (isset($data['phone'])) {
                // Attempt login with phone
                $credentials = ['phone' => $data['phone'], 'password' => $data['password']];
            }
            if (!Auth::attempt($credentials)) {
                return false;
            }
            $user = Auth::user();
            if ($user->hasRole('user')) {
                return false;
            }
            Auth::login($user);
            return $user;
        });
    }
    public function logout()
    {
        return DB::transaction(function () {

            Auth::guard('web')->logout();
        });

    }
}

