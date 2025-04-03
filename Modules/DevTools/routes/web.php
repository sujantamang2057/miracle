<?php

use Illuminate\Support\Facades\Route;
use Modules\DevTools\app\Http\Controllers\InfoController;
use Modules\DevTools\app\Http\Controllers\RouteListController;

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

// RBAC
Route::group([
    'middleware' => ['auth'],
    'prefix' => 'devtools',
], function () {
    Route::get('/route-lists', [RouteListController::class, 'index'])->name('devtools.dev.index');
    Route::post('/route-lists', [RouteListController::class, 'route'])->name('devtools.dev.route');
    Route::get('/info/index', [InfoController::class, 'index'])->name('devtools.phpInfo.index');
});
