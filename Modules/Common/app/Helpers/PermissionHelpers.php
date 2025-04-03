<?php

/**
 * PermissionHelper.php
 * /app/Helpers/PermissionHelper.php
 */
function checkPermission($permissionName = '')
{
    return empty($permissionName) ? true : auth()->user()->can($permissionName);
}

function checkAnyPermission($permissions = [])
{
    return empty($permissions) ? true : auth()->user()->canAny($permissions);
}

function checkPermissionList($prefix, $permissions = [])
{
    if (empty($prefix)) {
        return false;
    }
    if (empty($permissions)) {
        return true;
    }

    if (is_array($permissions)) {
        $permissions = array_map(function ($item) use ($prefix) {
            return $prefix . $item;
        }, $permissions);
    } else {
        $permissions = $prefix . $permissions;
    }

    return checkAnyPermission($permissions);
}

function checkCmsAdminPermission($permissionName = '')
{
    return checkPermission('cmsadmin.' . $permissionName);
}

function checkCmsAdminPermissionList($permissions = [])
{
    return checkPermissionList('cmsadmin.', $permissions);
}

function checkBlogsPermission($permissionName = '')
{
    return checkPermission('blogs.' . $permissionName);
}

function checkBlogsPermissionList($permissions = [])
{
    return checkPermissionList('blogs.', $permissions);
}

function checkSysPermission($permissionName = '')
{
    return checkPermission('sys.' . $permissionName);
}

function checkSysPermissionList($permissions = [])
{
    return checkPermissionList('sys.', $permissions);
}

function checkToolsPermission($permissionName = '')
{
    return checkPermission('tools.' . $permissionName);
}

function checkToolsPermissionList($permissions = [])
{
    return checkPermissionList('tools.', $permissions);
}

/**
 * Build permission middleware string.
 *
 * @param  string  $context
 * @param  array  $permissions
 * @return string
 */
function buildPermissionMiddleware($context, $permissions)
{
    return 'permission:' . implode('|', array_map(fn ($perm) => "$context.$perm", $permissions));
}

/**
 * Build can middleware string.
 *
 * @param  string  $context
 * @param  array  $permissions
 * @return string
 */
function buildCanMiddleware($context, $permissions)
{
    return 'can:' . implode('|', array_map(fn ($perm) => "$context.$perm", $permissions));
}

function applyMiddleware($controller, $permissions = [])
{
    foreach ($permissions as $permission => $methods) {
        $controller->middleware($permission, ['only' => $methods]);
    }
}

function applyMiddlewareWithRole($controller, $permissions, $role)
{
    foreach ($permissions as $permission => $methods) {
        $controller->middleware(['role:' . $role, $permission], ['only' => $methods]);
    }
}

// Function to manage permission for Bootstrap Switch Box
function manageRenderBsSwitchGrid($permission, $fieldName, $id, $value, $route, $routeParam = [])
{
    // Check for empty or invalid inputs
    if (empty($permission) || empty($fieldName) || empty($id) || !is_numeric($id) || empty($value) || empty($route)) {
        return;
    }
    $route = !empty($routeParam) ? route($route, $routeParam) : route($route);
    $functionName = ($fieldName == 'publish') ? getPublishText($value) : (($fieldName == 'active') ? getActiveText($value) : getReservedText($value));
    if (checkCmsAdminPermission($permission) || checkSysPermission($permission) || checkBlogsPermission($permission)) {
        return renderBsSwitchGridEx($fieldName, $id, $value, $route);
    } else {
        return $functionName;
    }
}

// Function to manage permission for sidebar menu
function checkMenuAccess($controller, $module)
{
    $return = false;
    $newArr = [];
    $arr = [
        'index',
        'create',
        'edit',
        'show',
        'destroy',
        'regenerate',
        'clone',
        'togglePublish',
        'trashList',
        'imageEdit',
        'locale',
        'scanRoutePermissions',
        'storeScannedRoutePermissions',
        'getAllRoutes',
        'permission',
        'role',
        'removeImage',
        'reorder',
        'changePassword',
        'config',
        'optimize',
        'route',
        'bulkDelete',
        'delete',
        'download',
        'uploader',
        'getImageNames',
        'sendMail',
        'exportPdf',
        'loadResendMail',
        'resendMail',
        'cleanBlocks',
        'cleanPages',
        'cleanEmailTemplates',
        'cleanTemporaryFiles',
        'clearOldFiles',
        'permanentDestroy',
    ];
    foreach ($arr as $key => $value) {
        $newArr[] = $controller . '.' . $value;
    }
    $functionName = checkPermissionName($module);
    if ($functionName($newArr)) {
        $return = true;
    }

    return $return;
}

function checkPermissionName($moduleName)
{
    switch ($moduleName) {
        case 'cmsAdmin':
            $functionName = 'checkCmsAdminPermissionList';
            break;
        case 'sys':
            $functionName = 'checkSysPermissionList';
            break;
        case 'blogs':
            $functionName = 'checkBlogsPermissionList';
            break;
        case 'tools':
            $functionName = 'checkToolsPermissionList';
            break;
        default:
            $functionName = 'checkCmsAdminPermissionList';
    }

    return $functionName;
}
