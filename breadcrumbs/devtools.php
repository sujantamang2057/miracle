<?php

/**
 * breadcrumbs/dev-tools.php
 */

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Dev Tools
Breadcrumbs::for('dev_tools', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('common::backend.menu.dev_tools'));
});

// Dev Tools >> PHP Info
Breadcrumbs::for('php_info', function (BreadcrumbTrail $trail) {
    $trail->parent('dev_tools');
    $trail->push(__('devtools::common.php_info'), route('devtools.phpInfo.index'));
});

// Dev Tools >> Route Lists
Breadcrumbs::for('route_lists', function (BreadcrumbTrail $trail) {
    $trail->parent('dev_tools');
    $trail->push(__('devtools::common.route_lists'), route('devtools.dev.index'));
});
