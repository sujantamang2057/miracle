<?php

/**
 * FunctionsSysHtml.php
 * /app/Helpers/FunctionsSysHtml.php
 */
function renderDashboardCountHtml($urlPath, $publishedCount, $totalCount, $label, $bgColor = null, $icon = null)
{
    if (empty($urlPath) || empty($label) || !is_numeric($publishedCount) || !is_numeric($totalCount)) {
        return '';
    }

    // Generate the HTML
    $html = '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 col-6">';
    $html .= '<div class="small-box ' . ($bgColor ? $bgColor : '') . '"><div class="inner text-white">';
    $html .= '<h3>' . $publishedCount . '/' . $totalCount . '</h3>';
    $html .= '<p>' . $label . '</p></div>';

    // Add icon if provided
    if (!empty($icon)) {
        $html .= '<div class="icon"><i class="' . $icon . '"></i></div>';
    }

    // Add link
    $html .= '<a href="' . url($urlPath) . '" class="small-box-footer">' . __('common::general.more_info') . ' <i class="fa fa-arrow-circle-right text-white"></i></a></div></div>';

    return $html;
}

function getPublishIcon($publish)
{
    $iconClass = ($publish == 1) ? 'fa-check text-success' : 'fa-times text-danger';

    return '<i class="fa ' . $iconClass . ' mr-1"></i>';
}
