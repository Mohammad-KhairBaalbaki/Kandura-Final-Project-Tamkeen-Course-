<?php

namespace App\Services\Api;

use App\Events\DashboardNotificationRequested;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function register(array $data)
    {
        return DB::transaction(function () use ($data) {

            $user = User::create($data);
            $user->assignRole('user');
            //send notification to admin when user registered
            event(new DashboardNotificationRequested(
                'notify.users.registered',
                'User Registered',
                "User $user->name #{$user->id} was registered",
                [
                    'type' => 'admin',
                    'event' => 'registered',
                    'user_id' => $user->id,
                    'url' => route('users.show', $user->id)
                ]
            ));
            $user->wallet()->create([
                'user_id' => $user->id,
                'balance' => 0
            ]);
            $token = $user->createToken('api token')->plainTextToken;
            $user->access_token = $token;
            return $user;
        });
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
            $token = $user->createToken('api token')->plainTextToken;
            $user->access_token = $token;
            return $user;
        });
    }

}


