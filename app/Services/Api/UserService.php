<?php

namespace App\Services\Api;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        return DB::transaction(function () {

            return Auth::user();
        });
    }

    public function update(array $data, User $user)
    {
        return DB::transaction(function () use ($data, $user) {

            $user = $user->update($data);

            return $user;
        });
    }

    public function updatePhoto(array $data)
    {
        return DB::transaction(function () use ($data) {

            $user = Auth::user();

            if (isset($user->image)) {
                $url = $user->image->url;
                Storage::delete($url);
                $user->image()->delete();
            }
            $newUrl = FileService::uploadFile($data['image'], 'users');
            $user->image()->create([
                'url' => $newUrl,
            ]);

            return $user->load('image');
        });

    }
}
