<?php

/**
 * Shortcut Functions
 * /app/Helpers/Shortcut.php
 */

// get well formatted app locale
function getAppLocaleEx()
{
    $locale = app()->getLocale();

    return str_replace('_', '-', $locale);
}

/**
 * Checks if the given URL is a backend URL.
 *
 * @param  string  $requestedUrl  The URL to check.
 * @return bool True if the URL is a backend URL, false otherwise.
 */
function isBackendUrl($requestedUrl)
{
    $modules = collect(Module::allEnabled())->keys()->toArray();
    $moduleArr = [];
    if (!empty($modules)) {
        $moduleArr = array_map('strtolower', $modules);
        $moduleArr = Arr::where($moduleArr, function ($value) {
            return $value != 'cms';
        });
    }

    return in_array(true, array_map(function ($value) use ($requestedUrl) {
        return Str::startsWith($requestedUrl, '/' . $value);
    }, $moduleArr));
}

function getModuleIdentifier($separator = '-', $skipModuleName = false, $allCaps = true)
{
    $urlSegment1 = getSegmentFromUrl(1); // module/controller
    $urlSegment2 = getSegmentFromUrl(2); // controller/id
    $actionName = getActionName();
    $moduleIdentifier = '';
    if ($skipModuleName == false) {
        $moduleIdentifier = $urlSegment1 . $separator;
    }
    $moduleIdentifier .= (!empty($urlSegment2) ? ($urlSegment2 . $separator) : '') . $actionName;
    if ($allCaps == true) {
        $moduleIdentifier = strtoupper($moduleIdentifier);
    }

    return $moduleIdentifier;
}

function getBackendBodyClass($separator = '-', $skipModuleName = false)
{
    $urlSegment1 = getSegmentFromUrl(1); // module/controller
    $urlSegment2 = getSegmentFromUrl(2); // controller/id
    $actionName = getActionName();
    $bodyClass = '';
    if ($skipModuleName == false) {
        $bodyClass = $urlSegment1 . $separator;
    }
    $bodyClass .= (!empty($urlSegment2) ? ($urlSegment2 . $separator) : '') . $actionName;

    return $bodyClass;
}

function getFrontendBodyClass($separator = '-')
{
    $urlSegment1 = getSegmentFromUrl(1); // controller
    $actionName = getActionName();
    $bodyClass = (!empty($urlSegment1) ? ($urlSegment1 . $separator) : '') . $actionName;

    return $bodyClass;
}

function getSEOCode($separator = '-', $allCaps = true)
{
    $urlSegment1 = getSegmentFromUrl(1); // controller
    $actionName = getActionName();
    $seoCode = (!empty($urlSegment1) ? ($urlSegment1 . $separator) : '') . $actionName;
    if ($allCaps == true) {
        $seoCode = strtoupper($seoCode);
    }

    return $seoCode;
}
