<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Web\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        try {
            $roles = $this->roleService->index();
            return view('roles.index', compact('roles'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function create()
    {
        try {
            $permissions = $this->roleService->create();
            return view('roles.create', compact('permissions'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->roleService->store($request);
            return redirect()->route('roles.index');
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return back()->withInput()->withErrors([
                'form' => 'process failed try again later',
            ]);
        }
    }

    public function show(Role $role)
    {
        try {
            $role = $this->roleService->show($role);
            return view('roles.show', compact('role'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function edit(Role $role)
    {
        try {
            $data = $this->roleService->edit($role);
            $role = $data['role'];
            $permissions = $data['permissions'];
            return view('roles.edit', compact('role', 'permissions'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function update(Request $request, Role $role)
    {
        try {
            $this->roleService->update($request, $role);
            return redirect()->route('roles.index');
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return back()->withInput()->withErrors([
                'form' => 'process failed try again later',
            ]);
        }
    }

    public function destroy(Role $role)
    {
        try {
            $result = $this->roleService->destroy($role);
            if (is_array($result) && ($result['error'] ?? null) === 'role_has_users') {
                return back()->withErrors([
                    'form' => 'Cannot delete a role that has users.',
                ]);
            }
            return redirect()->route('roles.index');
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return back()->withErrors([
                'form' => 'process failed try again later',
            ]);
        }
    }
}
