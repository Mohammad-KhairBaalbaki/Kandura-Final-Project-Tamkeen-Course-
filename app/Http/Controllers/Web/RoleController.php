<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRoleRequest;
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
            $data = $this->roleService->create();
            $permissions = $data['permissions'];
            $groupUsers = $data['groupUsers'];
            $groupOrders = $data['groupOrders'];
            $groupDesigns = $data['groupDesigns'];
            $groupDesignOptions = $data['groupDesignOptions'];
            $groupCoupons = $data['groupCoupons'];
            $groupWallets = $data['groupWallets'];
            $groupNotifications = $data['groupNotifications'];
            $otherPermissions = $data['otherPermissions'];

            return view('roles.create', compact(
                'permissions',
                'groupUsers',
                'groupOrders',
                'groupDesigns',
                'groupDesignOptions',
                'groupCoupons',
                'groupWallets',
                'groupNotifications',
                'otherPermissions'
            ));
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
            $groupUsers = $data['groupUsers'];
            $groupOrders = $data['groupOrders'];
            $groupDesigns = $data['groupDesigns'];
            $groupDesignOptions = $data['groupDesignOptions'];
            $groupCoupons = $data['groupCoupons'];
            $groupWallets = $data['groupWallets'];
            $groupNotifications = $data['groupNotifications'];
            $otherPermissions = $data['otherPermissions'];

            return view('roles.edit', compact(
                'role',
                'permissions',
                'groupUsers',
                'groupOrders',
                'groupDesigns',
                'groupDesignOptions',
                'groupCoupons',
                'groupWallets',
                'groupNotifications',
                'otherPermissions'
            ));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        try {
            $this->roleService->update($request->validated(), $role);

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
