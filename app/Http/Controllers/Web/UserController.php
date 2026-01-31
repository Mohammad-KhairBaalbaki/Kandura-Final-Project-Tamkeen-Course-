<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Web\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    //
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        try {
            $data = $this->userService->index($request);
            return view('users.index', $data);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function show(User $user)
    {
        try {
            $user = $this->userService->show($user);
            return view('users.show', compact('user'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }



    // app/Http/Controllers/UserController.php
    public function updateStatus(Request $request, User $user)
    {
        try {
            $this->userService->updateStatus($request, $user);
            return back()->with('success', 'User status updated');
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

}
