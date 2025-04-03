<?php

/**
 * breadcrumbs/sys.php
 */

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Modules\Sys\app\Models\EmailTemplate;
use Modules\Sys\app\Models\MailerSetting;
use Modules\Sys\app\Models\Role;
use Modules\Sys\app\Models\SiteSetting;
use Modules\Sys\app\Models\Sns;
use Modules\Sys\app\Models\User;

// Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push(__('common::backend.menu.dashboard'), route('home'));
});
// SystemSetting >> siteSetting
Breadcrumbs::for('site_setting', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('sys::models/site_settings.singular'), route('sys.siteSettings.index'));
});

// SystemSetting >> siteSetting >> Create
Breadcrumbs::for('site_setting_create', function (BreadcrumbTrail $trail) {
    $trail->parent('site_setting');
    $trail->push(__('common::crud.create'), route('sys.siteSettings.create'));
});

// SystemSetting >> siteSetting >> Name
Breadcrumbs::for('site_setting_detail', function (BreadcrumbTrail $trail, SiteSetting $siteSetting) {
    $trail->parent('site_setting');
    $trail->push($siteSetting->site_name, route('sys.siteSettings.show', $siteSetting));
});

// SystemSetting >> siteSetting >> Name >> Edit
Breadcrumbs::for('site_setting_edit', function (BreadcrumbTrail $trail, SiteSetting $siteSetting) {
    $trail->parent('site_setting_detail', $siteSetting);
    $trail->push(__('common::crud.edit'), route('sys.siteSettings.edit', $siteSetting));
});

// MailSetting >> mailerSetting
Breadcrumbs::for('mailer_setting', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('sys::models/mailer_settings.singular'), route('sys.mailerSettings.index'));
});

// MailSetting >> mailerSetting >> Create
Breadcrumbs::for('mailer_setting_create', function (BreadcrumbTrail $trail) {
    $trail->parent('mailer_setting');
    $trail->push(__('common::crud.create'), route('sys.mailerSettings.create'));
});

// MailSetting >> mailerSetting >> Name
Breadcrumbs::for('mailer_setting_detail', function (BreadcrumbTrail $trail, MailerSetting $mailerSetting) {
    $trail->parent('mailer_setting');
    $trail->push(__('sys::models/mailer_settings.symfony'), route('sys.mailerSettings.show', $mailerSetting));
});

// MailSetting >> mailerSetting >> Name >> Edit
Breadcrumbs::for('mailer_setting_edit', function (BreadcrumbTrail $trail, MailerSetting $mailerSetting) {
    $trail->parent('mailer_setting_detail', $mailerSetting);
    $trail->push(__('common::crud.edit'), route('sys.mailerSettings.edit', $mailerSetting));
});

// MailSetting >> emailTemplate
Breadcrumbs::for('emailTemplate', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('sys::models/email_templates.singular'), route('sys.emailTemplates.index'));
});

// MailSetting >> emailTemplate >> Trash
Breadcrumbs::for('emailTemplate_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('emailTemplate');
    $trail->push(__('common::crud.trash'), route('sys.emailTemplates.trashList'));
});

// MailSetting >> emailTemplate >> Create
Breadcrumbs::for('emailTemplate_create', function (BreadcrumbTrail $trail) {
    $trail->parent('emailTemplate');
    $trail->push(__('common::crud.create'), route('sys.emailTemplates.create'));
});

// MailSetting >> emailTemplate >> Name
Breadcrumbs::for('emailTemplate_detail', function (BreadcrumbTrail $trail, EmailTemplate $emailTemplate) {
    $trail->parent('emailTemplate');
    $trail->push($emailTemplate->name, route('sys.emailTemplates.show', $emailTemplate));
});

// MailSetting >> emailTemplate >> Name >> Edit
Breadcrumbs::for('emailTemplate_edit', function (BreadcrumbTrail $trail, EmailTemplate $emailTemplate) {
    $trail->parent('emailTemplate_detail', $emailTemplate);
    $trail->push(__('common::crud.edit'), route('sys.emailTemplates.edit', $emailTemplate));
});

// User
Breadcrumbs::for('user', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('sys::models/users.singular'), route('sys.users.index'));
});

// User >> Trash
Breadcrumbs::for('user_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('user');
    $trail->push(__('common::crud.trash'), route('sys.users.trashList'));
});

// User >> Create
Breadcrumbs::for('user_create', function (BreadcrumbTrail $trail) {
    $trail->parent('user');
    $trail->push(__('common::crud.create'), route('sys.users.create'));
});

// User >> Name
Breadcrumbs::for('user_detail', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('user');
    $trail->push($user->name, route('sys.users.show', $user));
});

// User >> Name >> Edit
Breadcrumbs::for('user_edit', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('user_detail', $user);
    $trail->push(__('common::crud.edit'), route('sys.users.edit', $user));
});

// User >> Name >> Change Password
Breadcrumbs::for('user_change_password', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('user_detail', $user);
    $trail->push(__('common::crud.change_password'), route('sys.users.changePassword', $user));
});

// Profile >> Name
Breadcrumbs::for('user_profile', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('dashboard');
    $trail->push(__('sys::models/users.text.profile'));
    $trail->push($user->name, route('sys.profile.index'));
});

// Profile >> Name >> Change Password
Breadcrumbs::for('user_profile_change_password', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('user_profile', $user);
    $trail->push(__('common::crud.change_password'), route('sys.profile.changePassword'));
});

// SystemSetting >> sns
Breadcrumbs::for('sns', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('sys::models/sns.singular'), route('sys.sns.index'));
});

// SystemSetting >> sns >> Create
Breadcrumbs::for('sns_create', function (BreadcrumbTrail $trail) {
    $trail->parent('sns');
    $trail->push(__('common::crud.create'), route('sys.sns.create'));
});

// SystemSetting >> sns >> Name
Breadcrumbs::for('sns_detail', function (BreadcrumbTrail $trail, Sns $sns) {
    $trail->parent('sns');
    $trail->push($sns->title, route('sys.sns.show', $sns));
});

// SystemSetting >> sns >> Name >> Edit
Breadcrumbs::for('sns_edit', function (BreadcrumbTrail $trail, Sns $sns) {
    $trail->parent('sns_detail', $sns);
    $trail->push(__('common::crud.edit'), route('sys.sns.edit', $sns));
});

// RBAC
Breadcrumbs::for('rbac', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('sys::models/roles.text.rbac'), route('sys.rbac.index'));
});

Breadcrumbs::for('rbac_role', function (BreadcrumbTrail $trail) {
    $trail->parent('rbac');
    $trail->push(__('sys::models/roles.singular'));
});

Breadcrumbs::for('rbac_role_create', function (BreadcrumbTrail $trail) {
    $trail->parent('rbac_role');
    $trail->push(__('common::crud.create'));
});

Breadcrumbs::for('rbac_role_edit', function (BreadcrumbTrail $trail, Role $role) {
    $trail->parent('rbac_role');
    $trail->push($role->name);
    $trail->push(__('common::crud.edit'));
});

Breadcrumbs::for('rbac_permission', function (BreadcrumbTrail $trail) {
    $trail->parent('rbac');
    $trail->push(__('sys::models/permissions.singular'));
});

Breadcrumbs::for('rbac_permission_create', function (BreadcrumbTrail $trail) {
    $trail->parent('rbac_permission');
    $trail->push(__('common::crud.create'));
});

Breadcrumbs::for('rbac_permission_scan', function (BreadcrumbTrail $trail) {
    $trail->parent('rbac_permission');
    $trail->push(__('sys::models/permissions.text.scan_route_permissions'));
});
