<?php

namespace Modules\Sys\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\Common\app\Http\Controllers\BackendController;
use Modules\Sys\app\Models\Permission;
use Modules\Sys\app\Models\Role;
use Modules\Sys\app\Repositories\RoleRepository;

class RoleController extends BackendController
{
    public function __construct(RoleRepository $roleRepository)
    {
        $this->langFile = 'sys::models/roles';
        $this->repository = $roleRepository;
        $this->listRoute = route('sys.rbac.index');
        // Permission For Master Role Only
        $this->middleware(HAS_ROLE_MASTER_PERMISSION)->only(['create', 'store', 'edit', 'update']);
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = $this->__getPermissions();

        return view('sys::roles.create')
            ->with('id', null)
            ->with('permissions', $permissions);
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        // sanitize inputs
        $request = $this->__sanitize($request);
        $this->__validate($request);
        $permissions = $request->permissions;
        $input = $request->except(['permissions']);

        $role = $this->repository->create($input);
        if (!empty($permissions)) {
            $permissions = is_array($permissions) ? $permissions : explode(',', $permissions);
            $role->permissions()->sync($permissions);
        }

        Flash::success(__('common::messages.saved', ['model' => __('sys::models/roles.singular')]))->important();

        return redirect(route('sys.rbac.index'));
    }

    /**
     * Show the form for editing a new role.
     */
    public function edit($id)
    {
        if (!$role = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $selectedPermissions = $role->permissions()->pluck('id')->toArray();
        $permissions = $this->__getPermissions($selectedPermissions);

        return view('sys::roles.edit')
            ->with('id', $id)
            ->with('role', $role)
            ->with('permissions', $permissions);
    }

    /**
     * update a role in storage.
     */
    public function update($id, Request $request)
    {
        if (!$role = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        // sanitize inputs
        $request = $this->__sanitize($request);
        $this->__validate($request, $id);
        $permissions = $request->permissions;
        $input = $request->except(['permissions']);

        $role = $this->repository->update($input, $id);
        if (!empty($permissions)) {
            $permissions = is_array($permissions) ? $permissions : explode(',', $permissions);
        }
        $role->permissions()->sync($permissions);

        Flash::success(__('common::messages.updated', ['model' => __('sys::models/roles.singular')]))->important();

        return redirect(route('sys.rbac.index'));
    }

    // sanitize inout
    private function __sanitize($request)
    {
        $request->merge([
            'guard_name' => 'web',
        ]);

        return $request;
    }

    // validate rules
    private function __validate($request, $id = null)
    {
        $rules = Role::$rules;
        $attributes = __('sys::models/roles.fields');
        if (!empty($id)) {
            unset($rules['name']);
        }

        $this->validate($request, $rules, [], $attributes);
    }

    private function __getPermissions($selected = '')
    {
        $selectedArr = [];
        if (!empty($selected)) {
            if (is_array($selected)) {
                $selectedArr = $selected;
            } else {
                $selectedArr = explode(',', $selected);
            }
        }
        $permissions = Permission::select(['id', 'name'])->get();
        // Formatting the permissions data
        $formattedPermissions = $permissions->map(function ($item) use ($selectedArr) {
            $data = [
                'id' => $item->id,
                'text' => $item->name,
            ];
            if (!empty($selectedArr) && in_array($item->id, $selectedArr)) {
                $data['selected'] = true;
            }

            return $data;
        })->toArray();

        return $formattedPermissions;
    }
}
