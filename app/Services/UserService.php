<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function profile()
    {
        return Auth::user();
    }

    public function update(array $data, User $user)
    {
        $user = $user->update($data);
        return $user;
    }

    
}
