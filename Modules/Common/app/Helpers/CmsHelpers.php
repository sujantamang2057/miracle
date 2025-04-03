<?php

/**
 * CmsHelper  Functions
 * /app/Helpers/CmsHelpers.php
 */

use Detection\MobileDetect;
use Modules\Sys\app\Models\SiteSetting;

// get database data from site_settings table
function getSiteSettings($field = null)
{
    if (empty($field)) {
        return '';
    }

    $result = SiteSetting::first();

    if (!empty($result)) {
        return isset($result[$field]) ? $result[$field] : null;
    } else {
        return false;
    }
}

function isMobile()
{
    $detect = new MobileDetect;

    return $detect->isMobile() && !$detect->isTablet();
}

function isTablet()
{
    $detect = new MobileDetect;

    return $detect->isTablet();
}

function isDesktop()
{
    $detect = new MobileDetect;

    return $detect->isDesktop();
}

function setActiveMenuCms($prefix)
{
    return request()->is($prefix) ? 'active' : '';
}
