<?php

namespace App\Http\Controllers;

use App\Services\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FcmTokenController extends Controller
{
    public function store(Request $request, FcmService $fcm)
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
        ]);

        $fcm->saveToken(
            Auth::user(),
            $data['token'],
        );

        return $this->success(true, 'Token saved successfully', 200);
    }
}
