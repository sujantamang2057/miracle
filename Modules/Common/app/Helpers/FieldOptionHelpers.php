<?php

/**
 * Field Option Helper Functions
 * /app/Helpers/FieldOptionHelpers.php
 */

// Extra Selection options for Drop-Down
function getExtraOption($index = '')
{
    return [$index => __('common::crud.text.select_any')];
}

// Active field
function getActiveList($key = null)
{
    $activeArray = ['1' => __('common::general.active'), '2' => __('common::general.inactive')];
    if (empty($key)) {
        return $activeArray;
    }
    if (array_key_exists($key, $activeArray)) {
        return $activeArray[$key];
    } else {
        return '';
    }
}

function getActiveText($key = null)
{
    if (empty($key)) {
        return '';
    }

    return getActiveList($key);
}

// Draft field
function getDraftList($key = null)
{
    $draftArray = ['1' => __('common::general.yes'), '2' => __('common::general.no')];
    if (empty($key)) {
        return $draftArray;
    }
    if (array_key_exists($key, $draftArray)) {
        return $draftArray[$key];
    } else {
        return '';
    }
}

function getDraftText($key = null)
{
    if (empty($key)) {
        return '';
    }

    return getDraftList($key);
}

// Publish field
function getPublishList($key = null)
{
    $publishArray = ['1' => __('common::general.published'), '2' => __('common::general.unpublished')];
    if (empty($key)) {
        return $publishArray;
    }
    if (array_key_exists($key, $publishArray)) {
        return $publishArray[$key];
    } else {
        return '';
    }
}

function getPublishText($key = null)
{
    if (empty($key)) {
        return '';
    }

    return getPublishList($key);
}

// Reserved field
function getReservedList($key = null)
{
    $reservedArray = ['1' => __('common::general.reserved'), '2' => __('common::general.nonreserved')];
    if (empty($key)) {
        return $reservedArray;
    }
    if (array_key_exists($key, $reservedArray)) {
        return $reservedArray[$key];
    } else {
        return '';
    }
}

function getReservedText($key = null)
{
    if (empty($key)) {
        return '';
    }

    return getReservedList($key);
}

// YesNo
function getYesNoList($key = null)
{
    $yesNoArray = ['1' => __('common::general.yes'), '2' => __('common::general.no')];
    if (empty($key)) {
        return $yesNoArray;
    }
    if (array_key_exists($key, $yesNoArray)) {
        return $yesNoArray[$key];
    } else {
        return '';
    }
}

function getYesNoText($key = null)
{
    if (empty($key)) {
        return '';
    }

    return getYesNoList($key);
}

// Page
// PageType field
function getPageTypeList($key = null)
{
    $pageTypeArray = ['1' => __('cmsadmin::models/pages.type_static'), '2' => __('cmsadmin::models/pages.type_dynamic')];
    if (empty($key)) {
        return $pageTypeArray;
    }
    if (array_key_exists($key, $pageTypeArray)) {
        return $pageTypeArray[$key];
    } else {
        return '';
    }
}

function getPageTypeText($key = null)
{
    if (empty($key)) {
        return '';
    }

    return getPageTypeList($key);
}

// Menu Module
// UrlTarget field
function getUrlTargetList($key = null)
{
    $urlTargetArray = ['1' => __('common::general.target_self'), '2' => __('common::general.target_new')];
    if (empty($key)) {
        return $urlTargetArray;
    }
    if (array_key_exists($key, $urlTargetArray)) {
        return $urlTargetArray[$key];
    } else {
        return '';
    }
}

function getUrlTargetText($key = null)
{
    if (empty($key)) {
        return '';
    }

    return getUrlTargetList($key);
}

// UrlType field
function getUrlTypeList($key = null)
{
    $urlTypeArray = ['1' => __('common::general.type_internal'), '2' => __('common::general.type_external')];
    if (empty($key)) {
        return $urlTypeArray;
    }
    if (array_key_exists($key, $urlTypeArray)) {
        return $urlTypeArray[$key];
    } else {
        return '';
    }
}

function getUrlTypeText($key = null)
{
    if (empty($key)) {
        return '';
    }

    return getUrlTypeList($key);
}

// SSL VERIFY LIST
function getSSLVerifyList($key = null)
{
    $sslVerifyArray = ['1' => __('common::general.true'), '2' => __('common::general.false')];
    if (empty($key)) {
        return $sslVerifyArray;
    }
    if (array_key_exists($key, $sslVerifyArray)) {
        return $sslVerifyArray[$key];
    } else {
        return '';
    }
}

function getSSLVerifyText($key = null)
{
    if (empty($key)) {
        return '';
    }

    return getSSLVerifyList($key);
}

function getfrontLinkButton($model, $routeName, $param)
{
    if ($routeName == 'url') {
        $route = url($param);
    } else {
        $route = route($routeName, $param);
    }
    if ($model->publish != 2) {
        return '<a href="' . $route . '" target="_blank" class="btn bg-primary btn-sm ml-1" style="margin-left: 10px;"> 
                    <i class="fas fa-external-link-alt"></i>
                </a>';
    }

    return '';

}
