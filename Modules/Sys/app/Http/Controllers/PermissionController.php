<?php

namespace Modules\Sys\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Modules\Common\app\Http\Controllers\BackendController;
use Modules\Sys\app\Models\Permission;
use Modules\Sys\app\Repositories\PermissionRepository;
use Nwidart\Modules\Facades\Module;
use Validator;

class PermissionController extends BackendController
{
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->langFile = 'sys::models/permissions';
        $this->repository = $permissionRepository;
        $this->listRoute = route('sys.rbac.index');
        // Permission For Master Role Only
        $this->middleware(HAS_ROLE_MASTER_PERMISSION)->only(['create', 'store', 'scanRoutePermissions', 'storeScannedRoutePermissions']);
    }

    public function scanRoutePermissions()
    {
        $moduleList = ['' => __('common::crud.text.select_any')] + $this->__getActiveModuleNames();

        return view('sys::permissions.scan', compact('moduleList'));
    }

    public function storeScannedRoutePermissions(Request $request)
    {
        $this->validate($request, [
            'module_name' => 'required',
        ]);
        $moduleName = $request->module_name;
        $lowerModuleName = strtolower($moduleName);
        $wildCardRoutes = [];
        $permissions = Permission::select('name')->where('name', 'like', $lowerModuleName . '.%')->pluck('name')->toArray();
        $routes = collect(Route::getRoutes())->filter(function ($item) use ($lowerModuleName, $permissions, &$wildCardRoutes) {
            if (isset($item->action['as']) && Str::startsWith($item->action['as'], $lowerModuleName . '.')) {
                $name = $item->action['as'];
                $nameArr = !empty($name) ? explode('.', $name) : '';

                if (!empty($nameArr) && isset($nameArr[2])) {
                    $nameArr[2] = '*';
                    $nameArr = array_splice($nameArr, 0, 3);
                    $wildCardRoute = implode('.', $nameArr);
                    if (!in_array($wildCardRoute, $permissions) && !in_array($wildCardRoute, $wildCardRoutes)) {
                        array_push($wildCardRoutes, $wildCardRoute);
                    }
                }

                return !in_array($item->action['as'], $permissions);
            }

            return false;
        })->values()->all();

        if (!empty($routes) || !empty($wildCardRoutes)) {
            $rules = Permission::$rules;
            $rules['name'] .= ',NULL,id,guard_name,web';
            $count = 0;
            if (!empty($routes)) {
                foreach ($routes as $key => $route) {
                    $name = isset($route->action['as']) ? $route->action['as'] : '';
                    if (!empty($name)) {
                        if ($this->__savePermission($name, $rules)) {
                            $count++;
                        } else {
                            continue;
                        }
                    }
                }
            }

            if (!empty($wildCardRoutes)) {
                foreach ($wildCardRoutes as $key => $wcRoute) {
                    if ($this->__savePermission($wcRoute, $rules)) {
                        $count++;
                    } else {
                        continue;
                    }
                }
            }

            if ($count) {
                Flash::success(__('sys::models/permissions.messages.routes_scanned', ['count' => $count, 'module' => $moduleName]))->important();
            } else {
                Flash::warning(__('sys::models/permissions.messages.no_new_routes_found', ['module' => $moduleName]))->important();
            }
        } else {
            Flash::warning(__('sys::models/permissions.messages.no_new_routes_found', ['module' => $moduleName]))->important();
        }

        return redirect(route('sys.rbac.index'));
    }

    private function __getActiveModuleNames()
    {
        $activeModules = Module::allEnabled();
        $modulesList = [];
        foreach ($activeModules as $module) {
            $modulesList[$module->getName()] = $module->getName();
        }

        return $modulesList;
    }

    private function __savePermission($name, $rules)
    {
        $data = [
            'name' => $name,
            'guard_name' => 'web',
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return false;
        } else {
            Permission::create($data);

            return true;
        }
    }
}
