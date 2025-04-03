<?php

/**
 * Datatable Helper Functions
 * /app/Helpers/DataTableHelpers.php
 */
function getCreateButton()
{
    return [
        'extend' => 'create',
        'text' => "<i class='fa fa-plus'></i> " . __('common::crud.create'),
        'className' => 'btn btn-success btn-sm no-corner',
    ];
}

function getResetButton()
{
    return [
        'extend' => 'reset',
        'text' => "<i class='fa fa-undo'></i> " . __('common::crud.reset'),
        'className' => 'btn btn-danger btn-sm no-corner',
    ];
}

function getReloadButton()
{
    return [
        'extend' => 'reload',
        'text' => "<i class='fa fa-sync'></i> " . __('common::crud.reload'),
        'className' => 'btn btn-warning btn-sm no-corner',
    ];
}
function getRegenerateButton()
{
    return [
        'extend' => 'reload',
        'text' => "<i class='fa fa-retweet'></i> " . __('common::crud.text.bulk_regenerate_option'),
        'className' => 'btn btn-success btn-sm no-corner',
        'action' => 'function (e, dt, node, config) {
            window.location.href = "' . route('cmsadmin.cspHeaders.regenerate') . '";
        }',
    ];
}

function getListButton($url = null)
{
    return [
        'text' => "<i class='fa fa-chevron-circle-left'></i> " . __('common::crud.manage'),
        'className' => 'btn btn-warning btn-sm no-corner',
        'action' => "function (e, dt, button, config) { window.location = '" . $url . "';}",
    ];
}

function getTrashButton($url = null)
{
    return [
        'text' => "<i class='fa fa-trash'></i> " . __('common::crud.trash'),
        'className' => 'btn btn-info btn-sm no-corner',
        'action' => "function (e, dt, button, config) { window.location = '" . $url . "';}",
    ];
}

function getApplyText()
{
    return [
        'text' => "<i class='fa fa-check'></i> " . __('common::crud.apply'),
        'className' => 'btn-apply-bulk btn-sm no-corner',
    ];
}

function getDataTableLanguageUrl()
{
    $tmpArr = config('app.available_locales') ? array_keys(config('app.available_locales')) : [];
    $appLang = app()->getLocale();
    $lang = in_array($appLang, $tmpArr) ? $appLang : config('app.locale');

    return asset("vendor/datatables/lang/$lang.json");
}

function getDatatableDOM()
{
    return 'Brt<"row align-items-center justify-content-between p-2"<""l><""i><""p>>';
}

/**
 * Target first column (index starts from 0)
 * Target last column (index starts from -1)
 * Priority lower value equates to a higher priority
 */
function getColumnDefsArr($targets = [], $priorities = [])
{
    $result = [];
    if (empty($targets) || empty($priorities)) {
        return $result;
    }
    if (count($targets) !== count($priorities)) {
        return $result;
    }
    for ($i = 0; $i < count($targets); $i++) {
        $result[] = [
            'targets' => $targets[$i],
            'responsivePriority' => $priorities[$i],
        ];
    }

    return $result;
}

function generateDataTableConfig($additionalConfig = [])
{
    $defaultConfig = [
        'width' => '100%',
        'class' => 'table table-striped table-bordered responsive no-wrap display',
    ];
    $config = array_merge($defaultConfig, $additionalConfig);

    return $config;
}
