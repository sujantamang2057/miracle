<?php

use Illuminate\Support\Facades\Route;
use Modules\Tools\app\Http\Controllers\BackupCleanerController;
use Modules\Tools\app\Http\Controllers\BackupController;
use Modules\Tools\app\Http\Controllers\CleanerController;
use Modules\Tools\app\Http\Controllers\FileManagerController;
use Modules\Tools\app\Http\Controllers\ImageCleanerController;
use Modules\Tools\app\Http\Controllers\ImageRegeneratorController;
use Modules\Tools\app\Http\Controllers\MailTesterController;
use Modules\Tools\app\Http\Controllers\SlugRegeneratorController;
use Modules\Tools\app\Http\Controllers\SpaceReportController;
use Modules\Tools\app\Http\Controllers\ToolsController;
use Modules\Tools\app\Http\Controllers\TrashDataPurgerController;

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
// Tools
Route::middleware('auth')->group(function () {
    // block
    Route::get('/tools', [ToolsController::class, 'index'])->name('tools.default.index');
});

Route::group(['prefix' => 'tools', 'middleware' => 'auth', 'as' => 'tools.'], function () {
    // cleaner
    Route::get('/cleaner/index', [CleanerController::class, 'index'])->name('cleaner.index');
    Route::get('/cleaner/cache', [CleanerController::class, 'cache'])->name('cleaner.cache');
    Route::get('/cleaner/config', [CleanerController::class, 'config'])->name('cleaner.config');
    Route::get('/cleaner/views', [CleanerController::class, 'views'])->name('cleaner.views');
    Route::get('/cleaner/route', [CleanerController::class, 'route'])->name('cleaner.route');
    Route::get('/cleaner/optimize', [CleanerController::class, 'optimize'])->name('cleaner.optimize');
    Route::get('/cleaner/permission_cache', [CleanerController::class, 'permissionCache'])->name('cleaner.permissionCache');

    // DB Backup
    Route::get('/backup/index', [BackupController::class, 'index'])->name('backup.index');
    Route::get('/backup/create', [BackupController::class, 'create'])->name('backup.create');
    Route::get('/backup/download', [BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/delete', [BackupController::class, 'delete'])->name('backup.delete');
    Route::delete('/backup/bulk_delete', [BackupController::class, 'bulkDelete'])->name('backup.bulkDelete');

    // File Manager
    Route::get('/filemanager/index', [FileManagerController::class, 'index'])->name('filemanager.index');
    Route::post('/filemanager/uploader', [FileManagerController::class, 'uploader'])->name('filemanager.uploader');

    // Space Report Tester
    Route::get('/space-report/index', [SpaceReportController::class, 'index'])->name('spaceReport.index');

    // Mail Tester
    Route::get('/mail-tester/index', [MailTesterController::class, 'index'])->name('mailTester.index');
    Route::post('/mail-tester/index', [MailTesterController::class, 'sendMail'])->name('mailTester.sendMail');

    // Slug Regenerator
    Route::get('/slug-regenerator/index', [SlugRegeneratorController::class, 'index'])->name('slugRegenerator.index');
    Route::post('/slug-regenerator/index', [SlugRegeneratorController::class, 'regenerate'])->name('slugRegenerator.regenerate');

    // Image Regenerator
    Route::get('/image-regenerator/index', [ImageRegeneratorController::class, 'index'])->name('imageRegenerator.index');
    Route::post('/image-regenerator/get-image-column', [ImageRegeneratorController::class, 'getImageNames'])->name('imageRegenerator.getImageNames');
    Route::post('/image-regenerator/index', [ImageRegeneratorController::class, 'regenerate'])->name('imageRegenerator.regenerate');

    // Trash Data Purger
    Route::get('/trash-data-purger/index', [TrashDataPurgerController::class, 'index'])->name('trashDataPurger.index');
    Route::post('/trash-data-purger/get-trash-data', [TrashDataPurgerController::class, 'getTrashedData'])->name('trashDataPurger.getTrashedData');
    Route::post('/trash-data-purger/purge-trash-data', [TrashDataPurgerController::class, 'purgeTrashedData'])->name('trashDataPurger.purgeTrashedData');

    // Backup Cleaner
    Route::get('/backup-cleaner/index', [BackupCleanerController::class, 'index'])->name('backupCleaner.index');
    Route::post('/backup-cleaner/clean-block', [BackupCleanerController::class, 'cleanBlocks'])->name('backupCleaner.cleanBlocks');
    Route::post('/backup-cleaner/clean-page', [BackupCleanerController::class, 'cleanPages'])->name('backupCleaner.cleanPages');
    Route::post('/backup-cleaner/clean-email-template', [BackupCleanerController::class, 'cleanEmailTemplates'])->name('backupCleaner.cleanEmailTemplates');

    // Image Cleaner
    Route::get('/image-cleaner/index', [ImageCleanerController::class, 'index'])->name('imageCleaner.index');
    Route::post('/image-cleaner/clean-temp-files', [ImageCleanerController::class, 'cleanTemporaryFiles'])->name('imageCleaner.cleanTemporaryFiles');
});

// redirect default elfinder
Route::get('/elfinder', function () {
    return redirect('/tools/filemanager');
});
