<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DesignPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function createDesign(){
        $user = User::findOrFail(Auth::id());
        if($user->hasPermissionTo('create-design')){
            return true;
        }
        return false;
    }
    public function editDesign(){
        $user = User::findOrFail(Auth::id());
        if($user->hasPermissionTo('edit-design')){
            return true;
        }
        return false;
    }
    public function deleteDesign(){
        $user = User::findOrFail(Auth::id());
        if($user->hasPermissionTo('delete-design')){
            return true;
        }
        return false;
    }
}
