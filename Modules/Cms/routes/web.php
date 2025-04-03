<?php

use Illuminate\Support\Facades\Route;
use Modules\Cms\app\Http\Controllers\BlogController;
use Modules\Cms\app\Http\Controllers\ContactController;
use Modules\Cms\app\Http\Controllers\FaqController;
use Modules\Cms\app\Http\Controllers\HomeController;
use Modules\Cms\app\Http\Controllers\ImageAlbumController;
use Modules\Cms\app\Http\Controllers\NewsController;
use Modules\Cms\app\Http\Controllers\PostController;
use Modules\Cms\app\Http\Controllers\ResourceController;
use Modules\Cms\app\Http\Controllers\SitemapController;
use Modules\Cms\app\Http\Controllers\TestimonialController;
use Modules\Cms\app\Http\Controllers\VideoAlbumController;

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
Route::group([], function () {
    Route::get('/', [HomeController::class, 'index'])->name('cms.home.index');
    Route::group(['prefix' => 'contact'], function () {
        Route::get('', [ContactController::class, 'index'])->name('cms.contact.index');
        Route::post('/confirm', [ContactController::class, 'confirm'])->name('cms.contact.confirm');
        Route::post('/complete', [ContactController::class, 'complete'])->name('cms.contact.complete');

        Route::get('/{page}', function () {
            return redirect(route('cms.contact.index'));
        })->where('page', 'confirm|complete');
    });
});

Route::any('post/search', [PostController::class, 'search'])->name('cms.posts.search');
Route::any('blog/search', [BlogController::class, 'search'])->name('cms.blogs.search');
Route::get('post', [PostController::class, 'index'])->name('cms.posts.index');
Route::get('post/{slug?}', [PostController::class, 'postList'])->name('cms.posts.postList');
Route::get('post/detail/{slug?}', [PostController::class, 'detail'])->name('cms.posts.detail');
Route::any('news/search', [NewsController::class, 'search'])->name('cms.news.search');
Route::get('news', [NewsController::class, 'index'])->name('cms.news.index');
Route::get('news/{slug?}', [NewsController::class, 'newsList'])->name('cms.news.newsList');
Route::get('news/detail/{slug?}', [NewsController::class, 'detail'])->name('cms.news.detail');
Route::get('image-album', [ImageAlbumController::class, 'index'])->name('cms.imageAlbums.index');
Route::get('image-album/{slug?}', [ImageAlbumController::class, 'galleryList'])->name('cms.imageAlbums.galleryList');
Route::get('video-album', [VideoAlbumController::class, 'index'])->name('cms.videoAlbums.index');
Route::get('video-album/{slug?}', [VideoAlbumController::class, 'gallery'])->name('cms.videoAlbums.gallery');
Route::get('resource', [ResourceController::class, 'index'])->name('cms.resources.index');
Route::post('resource/downloadCount', [ResourceController::class, 'downloadCount'])->name('cms.resources.downloadCount');
Route::get('testimonial', [TestimonialController::class, 'index'])->name('cms.testimonials.index');
Route::get('faq', [FaqController::class, 'index'])->name('cms.faqs.index');
Route::get('blog', [BlogController::class, 'index'])->name('cms.blogs.index');
Route::get('blog/{slug?}', [BlogController::class, 'blogList'])->name('cms.blogs.blogList');
Route::get('blog/detail/{slug?}', [BlogController::class, 'detail'])->name('cms.blogs.detail');
Route::get('sitemap/index', [SitemapController::class, 'index'])->name('cms.sitemap.index');

Route::fallback(function () {
    if (request()->isMethod('get')) {
        $slug = request()->path();
        if ($slug) {
            return app()->call('Modules\Cms\app\Http\Controllers\PageController@index', ['slug' => $slug]);
        }
    }
    abort(404);
});
