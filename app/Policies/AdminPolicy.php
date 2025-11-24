<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function addAdmin(){
        $user =User::findOrFail(Auth::id());
        if($user->hasPermissionTo('add-admin')){
            return true;
        }
        return false;
    }
    public function editAdmin(){
        $user =User::findOrFail(Auth::id());
        if($user->hasPermissionTo('edit-admin')){
            return true;
        }
        return false;
    }
    public function deleteAdmin(){
        $user =User::findOrFail(Auth::id());
        if($user->hasPermissionTo('delete-admin')){
            return true;
        }
        return false;
    }
}
