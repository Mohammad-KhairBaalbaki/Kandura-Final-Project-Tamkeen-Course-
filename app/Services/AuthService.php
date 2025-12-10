<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function apiRegister(array $data)
    {
        $user = User::create($data);
        $user->assignRole('user');
        $token = $user->createToken('api token')->plainTextToken;
        $user->access_token = $token;
        return $user;
    }

    public function apiLogin(array $data)
    {
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
        $token = $user->createToken('api token')->plainTextToken;
        $user->access_token = $token;
        return $user;
    }
    public function apiLogout()
    {

    }




    public function webLogin(array $data)
    {
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
    }
    public function webLogout()
    {
        Auth::guard('web')->logout();


    }
}
