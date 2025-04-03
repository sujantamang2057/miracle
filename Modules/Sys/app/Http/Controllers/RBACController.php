<?php

namespace Modules\Sys\app\Http\Controllers;

use Modules\Common\app\Http\Controllers\BackendController;
use Modules\Sys\app\DataTables\PermissionDataTable;
use Modules\Sys\app\DataTables\RoleDataTable;
use Response;

class RBACController extends BackendController
{
    public function __construct()
    {
        // Permission For Master Role Only
        $this->middleware(HAS_ROLE_MASTER_PERMISSION)->only(['index', 'getRole', 'getPermission']);
    }

    /**
     * Display a listing of the User and Notice.
     *
     * @return Response
     */
    public function index(RoleDataTable $roleDataTable, PermissionDataTable $permissionDataTable)
    {
        return view('sys::rbac.index', [
            'roleDataTable' => $roleDataTable->html(),
            'permissionDataTable' => $permissionDataTable->html(),
        ]);
    }

    public function getRole(RoleDataTable $roleDataTable)
    {
        return $roleDataTable->render('sys::rbac.index');
    }

    public function getPermission(PermissionDataTable $permissionDataTable)
    {
        return $permissionDataTable->render('sys::rbac.index');
    }
}
