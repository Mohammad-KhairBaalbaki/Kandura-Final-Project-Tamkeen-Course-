<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\Web\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    //
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function index(Request $request)
    {
        try {
            $data = $this->adminService->index($request);

            return view('admins.index', $data);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function show(User $user)
    {
        try {
            $user = $this->adminService->show($user);

            return view('admins.show', compact('user'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function create()
    {
        try {
            $roles = $this->adminService->create();

            return view('admins.create', compact('roles'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function store(RegisterRequest $request)
    {
        try {
            $result = $this->adminService->store($request->validated());
            if (! $result) {
                return back()
                    ->withInput()
                    ->withErrors(['service' => __('admins.not_authorized_add_admin')]);
            }

            return redirect()->route('admins.index');

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['service' => __('admins.create_failed')]);
        }
    }

    public function edit(User $user)
    {
        try {
            $data = $this->adminService->edit($user);
            $admin = $data['admin'];
            $roles = $data['roles'];

            return view('admins.edit', compact('admin', 'roles'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $result = $this->adminService->update($request->validated(), $user);
            if (! $result) {
                return back()
                    ->withInput()
                    ->withErrors(['service' => __('admins.not_authorized_edit_admin')]);
            }

            return redirect()->route('admins.index');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['service' => __('admins.update_failed')]);
        }
    }

    public function destroy(User $user)
    {
        try {
            $this->adminService->delete($user);

            return redirect()->route('admins.index');
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }
}
