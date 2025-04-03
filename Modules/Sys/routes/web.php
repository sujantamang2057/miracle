<?php

use Illuminate\Support\Facades\Route;
use Modules\Sys\app\Http\Controllers\DashboardController;
use Modules\Sys\app\Http\Controllers\EmailTemplateController;
use Modules\Sys\app\Http\Controllers\MailerSettingController;
use Modules\Sys\app\Http\Controllers\PermissionController;
use Modules\Sys\app\Http\Controllers\ProfileController;
use Modules\Sys\app\Http\Controllers\RBACController;
use Modules\Sys\app\Http\Controllers\RoleController;
use Modules\Sys\app\Http\Controllers\SiteSettingController;
use Modules\Sys\app\Http\Controllers\SnsController;
use Modules\Sys\app\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::prefix('sys')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('home');
        Route::post('/locale', [DashboardController::class, 'locale'])->name('sys.dashboard.locale');

        // site settings
        Route::resource('/site-setting', SiteSettingController::class)
            ->names([
                'index' => 'sys.siteSettings.index',
                'store' => 'sys.siteSettings.store',
                'show' => 'sys.siteSettings.show',
                'update' => 'sys.siteSettings.update',
                'destroy' => 'sys.siteSettings.destroy',
                'create' => 'sys.siteSettings.create',
                'edit' => 'sys.siteSettings.edit',
            ]);
        Route::post('/site-setting/{id}/remove-image', [SiteSettingController::class, 'removeImage'])->name('sys.siteSettings.removeImage');

        // mailer settings
        Route::resource('mailer-setting', MailerSettingController::class)->names([
            'index' => 'sys.mailerSettings.index',
            'store' => 'sys.mailerSettings.store',
            'show' => 'sys.mailerSettings.show',
            'update' => 'sys.mailerSettings.update',
            'destroy' => 'sys.mailerSettings.destroy',
            'create' => 'sys.mailerSettings.create',
            'edit' => 'sys.mailerSettings.edit',
        ]);

        // email templates
        Route::get('email-template/trash-list', [EmailTemplateController::class, 'trashList'])->name('sys.emailTemplates.trashList');
        Route::post('email-template/restore/{id}', [EmailTemplateController::class, 'restore'])->name('sys.emailTemplates.restore');
        Route::delete('email-template/permanent-destroy/{id}', [EmailTemplateController::class, 'permanentDestroy'])->name('sys.emailTemplates.permanentDestroy');
        Route::resource('email-templates', EmailTemplateController::class)
            ->names([
                'index' => 'sys.emailTemplates.index',
                'store' => 'sys.emailTemplates.store',
                'show' => 'sys.emailTemplates.show',
                'update' => 'sys.emailTemplates.update',
                'destroy' => 'sys.emailTemplates.destroy',
                'create' => 'sys.emailTemplates.create',
                'edit' => 'sys.emailTemplates.edit',
            ]);
        Route::post('email-template/toggle-publish', [EmailTemplateController::class, 'togglePublish'])->name('sys.emailTemplates.togglePublish');
        Route::post('email-template/toggle-reserved', [EmailTemplateController::class, 'toggleReserved'])->name('sys.emailTemplates.toggleReserved');
        Route::post('email-template/regenerate', [EmailTemplateController::class, 'regenerate'])->name('sys.emailTemplates.regenerate');

        // Profile
        Route::get('profile', [ProfileController::class, 'index'])->name('sys.profile.index');
        Route::patch('profile/update', [ProfileController::class, 'update'])->name('sys.profile.update');
        Route::get('profile/change-password', [ProfileController::class, 'changePassword'])->name('sys.profile.changePassword');
        Route::patch('profile/update-password', [ProfileController::class, 'updatePassword'])->name('sys.profile.updatePassword');

        // users
        Route::get('user/trash-list', [UserController::class, 'trashList'])->name('sys.users.trashList');
        Route::post('user/restore/{id}', [UserController::class, 'restore'])->name('sys.users.restore');
        Route::delete('user/permanent-destroy/{id}', [UserController::class, 'permanentDestroy'])->name('sys.users.permanentDestroy');
        Route::resource('user', UserController::class)
            ->names([
                'index' => 'sys.users.index',
                'store' => 'sys.users.store',
                'show' => 'sys.users.show',
                'update' => 'sys.users.update',
                'destroy' => 'sys.users.destroy',
                'create' => 'sys.users.create',
                'edit' => 'sys.users.edit',
            ]);
        Route::post('user/toggle-active', [UserController::class, 'toggleActive'])->name('sys.users.toggleActive');
        Route::get('user/{id}/image-edit/{field}', [UserController::class, 'imageEdit'])->name('sys.users.imageEdit');
        Route::patch('user/{id}/image-update/{field}', [UserController::class, 'imageUpdate'])->name('sys.users.imageUpdate');
        Route::get('user/{id}/change-password', [UserController::class, 'changePassword'])->name('sys.users.changePassword');
        Route::patch('user/{id}/update-password', [UserController::class, 'updatePassword'])->name('sys.users.updatePassword');
        Route::post('user/{id}/remove-image', [UserController::class, 'removeImage'])->name('sys.users.removeImage');

        // sns
        Route::resource('sns', SnsController::class)
            ->names([
                'index' => 'sys.sns.index',
                'store' => 'sys.sns.store',
                'show' => 'sys.sns.show',
                'update' => 'sys.sns.update',
                'destroy' => 'sys.sns.destroy',
                'create' => 'sys.sns.create',
                'edit' => 'sys.sns.edit',
            ]);
        Route::post('sns/toggle-publish', [SnsController::class, 'togglePublish'])->name('sys.sns.togglePublish');
        Route::post('sns/toggle-reserved', [SnsController::class, 'toggleReserved'])->name('sys.sns.toggleReserved');
        Route::post('sns/{id}/remove-image', [SnsController::class, 'removeImage'])->name('sys.sns.removeImage');
        Route::post('sns/reorder', [SnsController::class, 'reorder'])->name('sys.sns.reorder');
        Route::get('sns/{id}/image-edit/{field}', [SnsController::class, 'imageEdit'])->name('sys.sns.imageEdit');
        Route::patch('sns/{id}/image-update/{field}', [SnsController::class, 'imageUpdate'])->name('sys.sns.imageUpdate');

        // RBAC
        Route::get('rbac/index', [RBACController::class, 'index'])->name('sys.rbac.index');
        Route::get('rbac/role', [RBACController::class, 'getRole'])->name('sys.rbac.role');
        Route::get('rbac/permission', [RBACController::class, 'getPermission'])->name('sys.rbac.permission');
        // roles
        Route::get('role/create', [RoleController::class, 'create'])->name('sys.roles.create');
        Route::post('role/store', [RoleController::class, 'store'])->name('sys.roles.store');
        Route::get('role/{role}/edit', [RoleController::class, 'edit'])->name('sys.roles.edit');
        Route::patch('role/{role}', [RoleController::class, 'update'])->name('sys.roles.update');
        Route::delete('role/{role}', [RoleController::class, 'destroy'])->name('sys.roles.destroy');

        // permissions
        Route::get('permission/scan', [PermissionController::class, 'scanRoutePermissions'])->name('sys.permissions.scanRoutePermissions');
        Route::post('permission/scan', [PermissionController::class, 'storeScannedRoutePermissions'])->name('sys.permissions.storeScannedRoutePermissions');
    });
});
