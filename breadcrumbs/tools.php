<?php

/**
 * breadcrumbs/tools.php
 */

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Tools
Breadcrumbs::for('tools', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('common::backend.menu.tools'), route('tools.default.index'));
});

// Tools >> backup cleaner
Breadcrumbs::for('backup_cleaner', function (BreadcrumbTrail $trail) {
    $trail->parent('tools');
    $trail->push(__('tools::common.backup_cleaner'), route('tools.backupCleaner.index'));
});

// Tools >> cleaner
Breadcrumbs::for('cleaner', function (BreadcrumbTrail $trail) {
    $trail->parent('tools');
    $trail->push(__('tools::common.app_cleaner'), route('tools.cleaner.index'));
});

// Tools >> Trash Data Purger
Breadcrumbs::for('trash_data_purger', function (BreadcrumbTrail $trail) {
    $trail->parent('tools');
    $trail->push(__('tools::common.trash_data_purger'), route('tools.trashDataPurger.index'));
});

// Tools >> DB Backup
Breadcrumbs::for('db_backup', function (BreadcrumbTrail $trail) {
    $trail->parent('tools');
    $trail->push(__('tools::common.db_backup'), route('tools.backup.index'));
});

// Tools >> FileManager
Breadcrumbs::for('file_manager', function (BreadcrumbTrail $trail) {
    $trail->parent('tools');
    $trail->push(__('tools::common.file_manager'), route('tools.filemanager.index'));
});

// Tools >> image cleaner
Breadcrumbs::for('image_cleaner', function (BreadcrumbTrail $trail) {
    $trail->parent('tools');
    $trail->push(__('tools::common.image_cleaner'), route('tools.imageCleaner.index'));
});

// Tools >> MailTester
Breadcrumbs::for('mail_tester', function (BreadcrumbTrail $trail) {
    $trail->parent('tools');
    $trail->push(__('tools::common.mail_tester'), route('tools.mailTester.index'));
});

// Tools >> Space Report
Breadcrumbs::for('space_report', function (BreadcrumbTrail $trail) {
    $trail->parent('tools');
    $trail->push(__('tools::common.space_report'), route('tools.spaceReport.index'));
});

// Tools >> SlugRegenerator
Breadcrumbs::for('slug_regenerator', function (BreadcrumbTrail $trail) {
    $trail->parent('tools');
    $trail->push(__('tools::common.slug_regenerator'), route('tools.slugRegenerator.index'));
});

// Tools >> ImageRegenerator
Breadcrumbs::for('image_regenerator', function (BreadcrumbTrail $trail) {
    $trail->parent('tools');
    $trail->push(__('tools::common.image_regenerator'), route('tools.imageRegenerator.index'));
});
