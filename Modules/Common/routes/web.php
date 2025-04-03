<?php

use Illuminate\Support\Facades\Route;
use Modules\Common\app\Http\Controllers\ImageHandlerController;
use Modules\Common\app\Http\Controllers\SitemapController;

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

Route::group([
    'middleware' => ['auth'],
    'prefix' => 'common',
], function () {
    Route::post('/image-handler/fileupload', [ImageHandlerController::class, 'fileupload'])->name('common.imageHandler.fileupload');
    Route::delete('/image-handler/destroy', [ImageHandlerController::class, 'destroy'])->name('common.imageHandler.destroy');
});
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('cms.sitemap.xml');
