<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DesignOptionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function createDesignOption(){
        $user =User::findOrFail(Auth::id());
        if($user->hasPermissionTo('create-design-option')){
            return true;
        }
        return false;
    }
}
