<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdminService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function storeAdmin(array $data)
    {
        if(!Gate::allows('addAdmin', User::class))
            return false;
        $user = User::create($data);
        $user->assignRole('admin');
        return $user;
    }
    public function updateAdmin(array $data,User $user)
    {
        if(!Gate::allows('editAdmin', User::class))
            return false;
        $user->update($data);
        $user = User::findOrFail($user->id);
        return $user;
    }

    public function deleteAdmin(User $user)
    {
        if(!(Gate::allows('deleteAdmin', User::class)&&$user->hasRole('admin')))
            return false;
        $user->delete();
        return true;
    }
}
