<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function show()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                abort(403);
            }

            $user->load([
                'image',
                'roles',
                'permissions',
            ])->loadCount(['orders', 'designs']);

            return view('profile.show', compact('user'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }
}
