<?php

/**
 * Backend Helper Functions
 * /app/Helpers/BackendHelpers.php
 */

// match requests to set active menu
function setActiveMenu($requestItems = [], $isChild = false, $prefix = '')
{
    $menuClass = '';
    if (!empty($requestItems)) {
        $activeClass = 'active';
        $extraClass = '';
        foreach ($requestItems as $key => $request) {
            if (Request::is($prefix . $request)) {
                $extraClass = ($isChild === true) ? '' : ' menu-open';
                $menuClass = $activeClass . $extraClass;
                echo $menuClass;

                return;
            }
        }
    }

    return $menuClass;
}

// /cmsadmin
function setActiveMenuCmsAdmin($requestItems = [], $isChild = false)
{
    $prefix = 'cmsadmin/';
    echo setActiveMenu($requestItems, $isChild, $prefix);
}

// /sys
function setActiveMenuSys($requestItems = [], $isChild = false)
{
    $prefix = 'sys/';
    echo setActiveMenu($requestItems, $isChild, $prefix);
}

// /tools
function setActiveMenuTools($requestItems = [], $isChild = false)
{
    $prefix = 'tools/';
    echo setActiveMenu($requestItems, $isChild, $prefix);
}

// /devtools
function setActiveMenuDevTools($requestItems = [], $isChild = false)
{
    $prefix = 'devtools/';
    echo setActiveMenu($requestItems, $isChild, $prefix);
}

// /blogs
function setActiveMenuBlogs($requestItems = [], $isChild = false)
{
    $prefix = 'blogs/';
    echo setActiveMenu($requestItems, $isChild, $prefix);
}

// getSegmentFromUrl
function getSegmentFromUrl($segment = 1)
{
    $controllerName = Request::segment($segment);

    return $controllerName;
}

// getActionName
function getActionName()
{
    $action = \Route::currentRouteAction();
    // $actionName = explode('@', \Route::currentRouteAction())[1];

    if (!$action) {
        return null;
    }

    $actionName = explode('@', $action);

    return $actionName[1];
}

function getControllerName()
{
    $controllerNamespace = explode('@', \Route::currentRouteAction())[0];
    if (empty($controllerNamespace)) {
        return;
    }
    $reflection = new \ReflectionClass($controllerNamespace);

    return $reflection->getShortName();
}

// get available locales from app.php
function getLocaleList()
{
    return config('app.available_locales');
}

// disable locale toggler for some actions
function disableLocaleToggle()
{
    $actionName = getActionName();
    $exists = in_array($actionName, ENABLE_LOCALE_TOGGLE);

    return $exists ? false : true;
}

function imageToBase64($path)
{
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $type = $ext == 'svg' ? $ext . '+xml' : $ext;
    $data = file_get_contents($path);

    return 'data:image/' . $type . ';base64,' . base64_encode($data);
}
