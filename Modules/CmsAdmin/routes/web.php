<?php

use Illuminate\Support\Facades\Route;
use Modules\CmsAdmin\app\Http\Controllers\AlbumController;
use Modules\CmsAdmin\app\Http\Controllers\BannerController;
use Modules\CmsAdmin\app\Http\Controllers\BlockController;
use Modules\CmsAdmin\app\Http\Controllers\BlogCategoryController;
use Modules\CmsAdmin\app\Http\Controllers\BlogController;
use Modules\CmsAdmin\app\Http\Controllers\BlogDetailController;
use Modules\CmsAdmin\app\Http\Controllers\ContactController;
use Modules\CmsAdmin\app\Http\Controllers\CspHeaderController;
use Modules\CmsAdmin\app\Http\Controllers\FaqCategoryController;
use Modules\CmsAdmin\app\Http\Controllers\FaqController;
use Modules\CmsAdmin\app\Http\Controllers\GalleryController;
use Modules\CmsAdmin\app\Http\Controllers\MenuController;
use Modules\CmsAdmin\app\Http\Controllers\NewsCategoryController;
use Modules\CmsAdmin\app\Http\Controllers\NewsController;
use Modules\CmsAdmin\app\Http\Controllers\NewsDetailController;
use Modules\CmsAdmin\app\Http\Controllers\PageController;
use Modules\CmsAdmin\app\Http\Controllers\PageDetailController;
use Modules\CmsAdmin\app\Http\Controllers\PostCategoryController;
use Modules\CmsAdmin\app\Http\Controllers\PostController;
use Modules\CmsAdmin\app\Http\Controllers\PostDetailController;
use Modules\CmsAdmin\app\Http\Controllers\ResourceController;
use Modules\CmsAdmin\app\Http\Controllers\SeoController;
use Modules\CmsAdmin\app\Http\Controllers\TestimonialController;
use Modules\CmsAdmin\app\Http\Controllers\VideoAlbumController;
use Modules\CmsAdmin\app\Http\Controllers\VideoGalleryController;

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

    // Block
    Route::get('cmsadmin/block/trash-list', [BlockController::class, 'trashList'])->name('cmsadmin.blocks.trashList');
    Route::post('cmsadmin/block/restore/{id}', [BlockController::class, 'restore'])->name('cmsadmin.blocks.restore');
    Route::delete('cmsadmin/block/permanent-destroy/{id}', [BlockController::class, 'permanentDestroy'])->name('cmsadmin.blocks.permanentDestroy');
    Route::resource('cmsadmin/block', BlockController::class)
        ->names([
            'index' => 'cmsadmin.blocks.index',
            'store' => 'cmsadmin.blocks.store',
            'show' => 'cmsadmin.blocks.show',
            'update' => 'cmsadmin.blocks.update',
            'destroy' => 'cmsadmin.blocks.destroy',
            'create' => 'cmsadmin.blocks.create',
            'edit' => 'cmsadmin.blocks.edit',
        ]);
    Route::post('cmsadmin/block/toggle-publish', [BlockController::class, 'togglePublish'])->name('cmsadmin.blocks.togglePublish');
    Route::post('cmsadmin/block/toggle-reserved', [BlockController::class, 'toggleReserved'])->name('cmsadmin.blocks.toggleReserved');
    Route::post('cmsadmin/block/regenerate', [BlockController::class, 'regenerate'])->name('cmsadmin.blocks.regenerate');

    // page
    Route::get('cmsadmin/page/trash-list', [PageController::class, 'trashList'])->name('cmsadmin.pages.trashList');
    Route::post('cmsadmin/page/restore/{id}', [PageController::class, 'restore'])->name('cmsadmin.pages.restore');
    Route::any('cmsadmin/page/preview/{id?}', [PageController::class, 'preview'])->name('cmsadmin.pages.preview');
    Route::get('cmsadmin/page/{id}/clone', [PageController::class, 'clone'])->name('cmsadmin.pages.clone');
    Route::delete('cmsadmin/page/permanent-destroy/{id}', [PageController::class, 'permanentDestroy'])->name('cmsadmin.pages.permanentDestroy');
    Route::resource('cmsadmin/page', PageController::class)
        ->names([
            'index' => 'cmsadmin.pages.index',
            'store' => 'cmsadmin.pages.store',
            'show' => 'cmsadmin.pages.show',
            'update' => 'cmsadmin.pages.update',
            'destroy' => 'cmsadmin.pages.destroy',
            'create' => 'cmsadmin.pages.create',
            'edit' => 'cmsadmin.pages.edit',
        ]);
    Route::post('cmsadmin/page/{id}/remove-image', [PageController::class, 'removeImage'])->name('cmsadmin.pages.removeImage');
    Route::post('cmsadmin/page/toggle-publish', [PageController::class, 'togglePublish'])->name('cmsadmin.pages.togglePublish');
    Route::post('cmsadmin/page/toggle-reserved', [PageController::class, 'toggleReserved'])->name('cmsadmin.pages.toggleReserved');
    Route::post('cmsadmin/page/regenerate', [PageController::class, 'regenerate'])->name('cmsadmin.pages.regenerate');
    Route::get('cmsadmin/page/{page}/multidata', [PageController::class, 'multidata'])->name('cmsadmin.pages.multidata');
    Route::post('cmsadmin/page/{page}/multidata', [PageController::class, 'saveMultidata'])->name('cmsadmin.pages.saveMultidata');
    Route::post('cmsadmin/page/reorder', [PageController::class, 'reorder'])->name('cmsadmin.pages.reorder');
    Route::get('cmsadmin/page/{id}/image-edit/{field}', [PageController::class, 'imageEdit'])->name('cmsadmin.pages.imageEdit');
    Route::patch('cmsadmin/page/{id}/image-update/{field}', [PageController::class, 'imageUpdate'])->name('cmsadmin.pages.imageUpdate');
    Route::get('cmsadmin/page/{page}/page-detail', [PageDetailController::class, 'index'])->name('cmsadmin.pageDetails.index');
    Route::delete('cmsadmin/page/{page}/page-detail/{pageDetail}', [PageDetailController::class, 'destroy'])->name('cmsadmin.pageDetails.destroy');
    Route::post('cmsadmin/page/{page}/page-detail/toggle-publish', [PageDetailController::class, 'togglePublish'])->name('cmsadmin.pageDetails.togglePublish');
    Route::post('cmsadmin/page/{page}/page-detail/reorder', [PageDetailController::class, 'reorder'])->name('cmsadmin.pageDetails.reorder');

    Route::get('cmsadmin/blog-category/trash-list', [BlogCategoryController::class, 'trashList'])->name('cmsadmin.blogCategories.trashList');
    Route::post('cmsadmin/blog-category/restore/{id}', [BlogCategoryController::class, 'restore'])->name('cmsadmin.blogCategories.restore');
    Route::delete('cmsadmin/blog-category/permanent-destroy/{id}', [BlogCategoryController::class, 'permanentDestroy'])->name('cmsadmin.blogCategories.permanentDestroy');

    Route::resource('cmsadmin/blog-category', BlogCategoryController::class)
        ->names([
            'index' => 'cmsadmin.blogCategories.index',
            'store' => 'cmsadmin.blogCategories.store',
            'show' => 'cmsadmin.blogCategories.show',
            'update' => 'cmsadmin.blogCategories.update',
            'destroy' => 'cmsadmin.blogCategories.destroy',
            'create' => 'cmsadmin.blogCategories.create',
            'edit' => 'cmsadmin.blogCategories.edit',
        ]);
    Route::post('cmsadmin/blog-category/{id}/remove-image', [BlogCategoryController::class, 'removeImage'])->name('cmsadmin.blogCategories.removeImage');
    Route::post('cmsadmin/blog-category/toggle-publish', [BlogCategoryController::class, 'togglePublish'])->name('cmsadmin.blogCategories.togglePublish');
    Route::post('cmsadmin/blog-category/toggle-reserved', [BlogCategoryController::class, 'toggleReserved'])->name('cmsadmin.blogCategories.toggleReserved');
    Route::post('cmsadmin/blog-category/reorder', [BlogCategoryController::class, 'reorder'])->name('cmsadmin.blogCategories.reorder');
    Route::get('cmsadmin/blog-category/{id}/image-edit/{field}', [BlogCategoryController::class, 'imageEdit'])->name('cmsadmin.blogCategories.imageEdit');
    Route::patch('cmsadmin/blog-category/{id}/image-update/{field}', [BlogCategoryController::class, 'imageUpdate'])->name('cmsadmin.blogCategories.imageUpdate');

    // blog
    Route::get('cmsadmin/blog/trash-list', [BlogController::class, 'trashList'])->name('cmsadmin.blogs.trashList');
    Route::post('cmsadmin/blog/restore/{id}', [BlogController::class, 'restore'])->name('cmsadmin.blogs.restore');
    Route::any('cmsadmin/blog/preview/{id?}', [BlogController::class, 'preview'])->name('cmsadmin.blogs.preview');
    Route::delete('cmsadmin/blog/permanent-destroy/{id}', [BlogController::class, 'permanentDestroy'])->name('cmsadmin.blogs.permanentDestroy');
    Route::resource('cmsadmin/blog', BlogController::class)
        ->names([
            'index' => 'cmsadmin.blogs.index',
            'store' => 'cmsadmin.blogs.store',
            'show' => 'cmsadmin.blogs.show',
            'update' => 'cmsadmin.blogs.update',
            'destroy' => 'cmsadmin.blogs.destroy',
            'create' => 'cmsadmin.blogs.create',
            'edit' => 'cmsadmin.blogs.edit',
        ]);
    Route::post('cmsadmin/blog/{id}/remove-image', [BlogController::class, 'removeImage'])->name('cmsadmin.blogs.removeImage');
    Route::post('cmsadmin/blog/toggle-publish', [BlogController::class, 'togglePublish'])->name('cmsadmin.blogs.togglePublish');
    Route::get('cmsadmin/blog/{blog}/multidata', [BlogController::class, 'multidata'])->name('cmsadmin.blogs.multidata');
    Route::post('cmsadmin/blog/{blog}/multidata', [BlogController::class, 'saveMultidata'])->name('cmsadmin.blogs.saveMultidata');
    Route::post('cmsadmin/blog/reorder', [BlogController::class, 'reorder'])->name('cmsadmin.blogs.reorder');
    Route::get('cmsadmin/blog/{id}/image-edit/{field}', [BlogController::class, 'imageEdit'])->name('cmsadmin.blogs.imageEdit');
    Route::patch('cmsadmin/blog/{id}/image-update/{field}', [BlogController::class, 'imageUpdate'])->name('cmsadmin.blogs.imageUpdate');
    Route::get('cmsadmin/blog/{blog}/blog-detail', [BlogDetailController::class, 'index'])->name('cmsadmin.blogDetails.index');
    Route::delete('cmsadmin/blog/{blog}/blog-detail/{blogDetail}', [BlogDetailController::class, 'destroy'])->name('cmsadmin.blogDetails.destroy');
    Route::post('cmsadmin/blog/{blog}/blog-detail/toggle-publish', [BlogDetailController::class, 'togglePublish'])->name('cmsadmin.blogDetails.togglePublish');
    Route::post('cmsadmin/blog/{blog}/blog-detail/reorder', [BlogDetailController::class, 'reorder'])->name('cmsadmin.blogDetails.reorder');

    // Post category
    Route::get('cmsadmin/post-category/trash-list', [PostCategoryController::class, 'trashList'])->name('cmsadmin.postCategories.trashList');
    Route::post('cmsadmin/post-category/restore/{id}', [PostCategoryController::class, 'restore'])->name('cmsadmin.postCategories.restore');
    Route::delete('cmsadmin/post-category/permanent-destroy/{id}', [PostCategoryController::class, 'permanentDestroy'])->name('cmsadmin.postCategories.permanentDestroy');

    Route::resource('cmsadmin/post-category', PostCategoryController::class)->names([
        'index' => 'cmsadmin.postCategories.index',
        'store' => 'cmsadmin.postCategories.store',
        'show' => 'cmsadmin.postCategories.show',
        'update' => 'cmsadmin.postCategories.update',
        'destroy' => 'cmsadmin.postCategories.destroy',
        'create' => 'cmsadmin.postCategories.create',
        'edit' => 'cmsadmin.postCategories.edit',
    ]);

    Route::post('cmsadmin/post-category/{id}/remove-image', [PostCategoryController::class, 'removeImage'])->name('cmsadmin.postCategories.removeImage');
    Route::post('cmsadmin/post-category/toggle-publish', [PostCategoryController::class, 'togglePublish'])->name('cmsadmin.postCategories.togglePublish');
    Route::post('cmsadmin/post-category/toggle-reserved', [PostCategoryController::class, 'toggleReserved'])->name('cmsadmin.postCategories.toggleReserved');
    Route::post('cmsadmin/post-category/reorder', [PostCategoryController::class, 'reorder'])->name('cmsadmin.postCategories.reorder');
    Route::get('cmsadmin/post-category/{id}/image-edit/{field}', [PostCategoryController::class, 'imageEdit'])->name('cmsadmin.postCategories.imageEdit');
    Route::patch('cmsadmin/post-category/{id}/image-update/{field}', [PostCategoryController::class, 'imageUpdate'])->name('cmsadmin.postCategories.imageUpdate');

    // Post
    Route::get('cmsadmin/post/trash-list', [PostController::class, 'trashList'])->name('cmsadmin.posts.trashList');
    Route::post('cmsadmin/post/restore/{id}', [PostController::class, 'restore'])->name('cmsadmin.posts.restore');
    Route::any('cmsadmin/post/preview/{id?}', [PostController::class, 'preview'])->name('cmsadmin.posts.preview');
    Route::delete('cmsadmin/post/permanent-destroy/{id}', [PostController::class, 'permanentDestroy'])->name('cmsadmin.posts.permanentDestroy');
    Route::resource('cmsadmin/post', PostController::class)
        ->names([
            'index' => 'cmsadmin.posts.index',
            'store' => 'cmsadmin.posts.store',
            'show' => 'cmsadmin.posts.show',
            'update' => 'cmsadmin.posts.update',
            'destroy' => 'cmsadmin.posts.destroy',
            'create' => 'cmsadmin.posts.create',
            'edit' => 'cmsadmin.posts.edit',
        ]);
    Route::post('cmsadmin/post/{id}/remove-image', [PostController::class, 'removeImage'])->name('cmsadmin.posts.removeImage');
    Route::post('cmsadmin/post/toggle-publish', [PostController::class, 'togglePublish'])->name('cmsadmin.posts.togglePublish');
    Route::get('cmsadmin/post/{post}/multidata', [PostController::class, 'multidata'])->name('cmsadmin.posts.multidata');
    Route::post('cmsadmin/post/{post}/multidata', [PostController::class, 'saveMultidata'])->name('cmsadmin.posts.saveMultidata');
    Route::post('cmsadmin/post/reorder', [PostController::class, 'reorder'])->name('cmsadmin.posts.reorder');
    Route::get('cmsadmin/post/{id}/image-edit/{field}', [PostController::class, 'imageEdit'])->name('cmsadmin.posts.imageEdit');
    Route::patch('cmsadmin/post/{id}/image-update/{field}', [PostController::class, 'imageUpdate'])->name('cmsadmin.posts.imageUpdate');
    Route::get('cmsadmin/post/{post}/post-detail', [PostDetailController::class, 'index'])->name('cmsadmin.postDetails.index');
    Route::delete('cmsadmin/post/{post}/post-detail/{postDetail}', [PostDetailController::class, 'destroy'])->name('cmsadmin.postDetails.destroy');
    Route::post('cmsadmin/post/{post}/post-detail/toggle-publish', [PostDetailController::class, 'togglePublish'])->name('cmsadmin.postDetails.togglePublish');
    Route::post('cmsadmin/post/{post}/post-detail/reorder', [PostDetailController::class, 'reorder'])->name('cmsadmin.postDetails.reorder');

    // News category
    Route::get('cmsadmin/news-category/trash-list', [NewsCategoryController::class, 'trashList'])->name('cmsadmin.newsCategories.trashList');
    Route::post('cmsadmin/news-category/restore/{id}', [NewsCategoryController::class, 'restore'])->name('cmsadmin.newsCategories.restore');
    Route::delete('cmsadmin/news-category/permanent-destroy/{id}', [NewsCategoryController::class, 'permanentDestroy'])->name('cmsadmin.newsCategories.permanentDestroy');
    Route::resource('cmsadmin/news-category', NewsCategoryController::class)
        ->names([
            'index' => 'cmsadmin.newsCategories.index',
            'store' => 'cmsadmin.newsCategories.store',
            'show' => 'cmsadmin.newsCategories.show',
            'update' => 'cmsadmin.newsCategories.update',
            'destroy' => 'cmsadmin.newsCategories.destroy',
            'create' => 'cmsadmin.newsCategories.create',
            'edit' => 'cmsadmin.newsCategories.edit',
        ]);
    Route::post('cmsadmin/news-category/toggle-publish', [NewsCategoryController::class, 'togglePublish'])->name('cmsadmin.newsCategories.togglePublish');
    Route::post('cmsadmin/news-category/toggle-reserved', [NewsCategoryController::class, 'toggleReserved'])->name('cmsadmin.newsCategories.toggleReserved');
    Route::post('cmsadmin/news-category/reorder', [NewsCategoryController::class, 'reorder'])->name('cmsadmin.newsCategories.reorder');

    // News
    Route::get('cmsadmin/news/trash-list', [NewsController::class, 'trashList'])->name('cmsadmin.news.trashList');
    Route::post('cmsadmin/news/restore/{id}', [NewsController::class, 'restore'])->name('cmsadmin.news.restore');
    Route::any('cmsadmin/news/preview/{id?}', [NewsController::class, 'preview'])->name('cmsadmin.news.preview');
    Route::delete('cmsadmin/news/permanent-destroy/{id}', [NewsController::class, 'permanentDestroy'])->name('cmsadmin.news.permanentDestroy');
    Route::resource('cmsadmin/news', NewsController::class)
        ->names([
            'index' => 'cmsadmin.news.index',
            'store' => 'cmsadmin.news.store',
            'show' => 'cmsadmin.news.show',
            'update' => 'cmsadmin.news.update',
            'destroy' => 'cmsadmin.news.destroy',
            'create' => 'cmsadmin.news.create',
            'edit' => 'cmsadmin.news.edit',
        ]);
    Route::post('cmsadmin/news/{id}/remove-image', [NewsController::class, 'removeImage'])->name('cmsadmin.news.removeImage');
    Route::post('cmsadmin/news/toggle-publish', [NewsController::class, 'togglePublish'])->name('cmsadmin.news.togglePublish');
    Route::post('cmsadmin/news/reorder', [NewsController::class, 'reorder'])->name('cmsadmin.news.reorder');
    Route::get('cmsadmin/news/{news}/multidata', [NewsController::class, 'multidata'])->name('cmsadmin.news.multidata');
    Route::post('cmsadmin/news/{news}/multidata', [NewsController::class, 'saveMultidata'])->name('cmsadmin.news.saveMultidata');
    Route::get('cmsadmin/news/{id}/image-edit/{field}', [NewsController::class, 'imageEdit'])->name('cmsadmin.news.imageEdit');
    Route::patch('cmsadmin/news/{id}/image-update/{field}', [NewsController::class, 'imageUpdate'])->name('cmsadmin.news.imageUpdate');
    Route::get('cmsadmin/news/{news}/news-detail', [NewsDetailController::class, 'index'])->name('cmsadmin.newsDetails.index');
    Route::delete('cmsadmin/news/{news}/news-detail/{newsDetail}', [NewsDetailController::class, 'destroy'])->name('cmsadmin.newsDetails.destroy');
    Route::post('cmsadmin/news/{news}/news-detail/toggle-publish', [NewsDetailController::class, 'togglePublish'])->name('cmsadmin.newsDetails.togglePublish');
    Route::post('cmsadmin/news/{news}/news-detail/reorder', [NewsDetailController::class, 'reorder'])->name('cmsadmin.newsDetails.reorder');

    // album
    Route::get('cmsadmin/album/trash-list', [AlbumController::class, 'trashList'])->name('cmsadmin.albums.trashList');
    Route::post('cmsadmin/album/restore/{id}', [AlbumController::class, 'restore'])->name('cmsadmin.albums.restore');
    Route::delete('cmsadmin/album/permanent-destroy/{id}', [AlbumController::class, 'permanentDestroy'])->name('cmsadmin.albums.permanentDestroy');
    Route::resource('cmsadmin/album', AlbumController::class)
        ->names([
            'index' => 'cmsadmin.albums.index',
            'store' => 'cmsadmin.albums.store',
            'show' => 'cmsadmin.albums.show',
            'update' => 'cmsadmin.albums.update',
            'destroy' => 'cmsadmin.albums.destroy',
            'create' => 'cmsadmin.albums.create',
            'edit' => 'cmsadmin.albums.edit',
        ]);
    Route::post('cmsadmin/album/toggle-publish', [AlbumController::class, 'togglePublish'])->name('cmsadmin.albums.togglePublish');
    Route::post('cmsadmin/album/toggle-reserved', [AlbumController::class, 'toggleReserved'])->name('cmsadmin.albums.toggleReserved');
    Route::patch('cmsadmin/album/{album}/set-cover-image', [AlbumController::class, 'setCoverImage'])->name('cmsadmin.albums.setCoverImage');
    Route::post('cmsadmin/album/reorder', [AlbumController::class, 'reorder'])->name('cmsadmin.albums.reorder');

    // gallery
    Route::post('cmsadmin/gallery/reorder', [GalleryController::class, 'reorder'])->name('cmsadmin.galleries.reorder');
    Route::get('cmsadmin/album/{album}/gallery', [GalleryController::class, 'index'])->name('cmsadmin.galleries.index');
    Route::delete('cmsadmin/album/{album}/gallery/{gallery}', [GalleryController::class, 'destroy'])->name('cmsadmin.galleries.destroy');
    Route::post('cmsadmin/album/{album}/gallery/{gallery}/removeGalleryImage', [GalleryController::class, 'removeGalleryImage'])->name('cmsadmin.galleries.removeGalleryImage');
    Route::post('cmsadmin/album/{album}/gallery/toggle-publish', [GalleryController::class, 'togglePublish'])->name('cmsadmin.galleries.togglePublish');
    Route::patch('cmsadmin/album/{album}/gallery/{gallery}/editable', [GalleryController::class, 'editable'])->name('cmsadmin.galleries.editable');
    Route::get('cmsadmin/album/{id}/image-edit/{field}', [GalleryController::class, 'imageEdit'])->name('cmsadmin.galleries.imageEdit');
    Route::patch('cmsadmin/album/{id}/image-update/{field}', [GalleryController::class, 'imageUpdate'])->name('cmsadmin.galleries.imageUpdate');

    // video-album
    Route::get('cmsadmin/video-album/trash-list', [VideoAlbumController::class, 'trashList'])->name('cmsadmin.videoAlbums.trashList');
    Route::post('cmsadmin/video-album/restore/{id}', [VideoAlbumController::class, 'restore'])->name('cmsadmin.videoAlbums.restore');
    Route::delete('cmsadmin/video-album/permanent-destroy/{id}', [VideoAlbumController::class, 'permanentDestroy'])->name('cmsadmin.videoAlbums.permanentDestroy');
    Route::resource('cmsadmin/video-album', VideoAlbumController::class)
        ->names([
            'index' => 'cmsadmin.videoAlbums.index',
            'store' => 'cmsadmin.videoAlbums.store',
            'show' => 'cmsadmin.videoAlbums.show',
            'update' => 'cmsadmin.videoAlbums.update',
            'destroy' => 'cmsadmin.videoAlbums.destroy',
            'create' => 'cmsadmin.videoAlbums.create',
            'edit' => 'cmsadmin.videoAlbums.edit',
        ]);
    Route::post('cmsadmin/video-album/toggle-publish', [VideoAlbumController::class, 'togglePublish'])->name('cmsadmin.videoAlbums.togglePublish');
    Route::post('cmsadmin/video-album/toggle-reserved', [VideoAlbumController::class, 'toggleReserved'])->name('cmsadmin.videoAlbums.toggleReserved');

    // menu
    Route::get('cmsadmin/menu/trash-list', [MenuController::class, 'trashList'])->name('cmsadmin.menus.trashList');
    Route::post('cmsadmin/menu/restore/{id}', [MenuController::class, 'restore'])->name('cmsadmin.menus.restore');
    Route::delete('cmsadmin/menu/permanent-destroy/{id}', [MenuController::class, 'permanentDestroy'])->name('cmsadmin.menus.permanentDestroy');
    Route::resource('cmsadmin/menu', MenuController::class)
        ->names([
            'index' => 'cmsadmin.menus.index',
            'store' => 'cmsadmin.menus.store',
            'show' => 'cmsadmin.menus.show',
            'update' => 'cmsadmin.menus.update',
            'destroy' => 'cmsadmin.menus.destroy',
            'create' => 'cmsadmin.menus.create',
            'edit' => 'cmsadmin.menus.edit',
        ]);
    Route::post('cmsadmin/menu/toggle-active', [MenuController::class, 'toggleActive'])->name('cmsadmin.menus.toggleActive');
    Route::post('cmsadmin/menu/toggle-reserved', [MenuController::class, 'toggleReserved'])->name('cmsadmin.menus.toggleReserved');
    Route::post('cmsadmin/menu/reorder', [MenuController::class, 'reorder'])->name('cmsadmin.menus.reorder');

    // /banner
    Route::get('cmsadmin/banner/trash-list', [BannerController::class, 'trashList'])->name('cmsadmin.banners.trashList');
    Route::post('cmsadmin/banner/restore/{id}', [BannerController::class, 'restore'])->name('cmsadmin.banners.restore');
    Route::delete('cmsadmin/banner/permanent-destroy/{id}', [BannerController::class, 'permanentDestroy'])->name('cmsadmin.banners.permanentDestroy');
    Route::resource('cmsadmin/banner', BannerController::class)
        ->names([
            'index' => 'cmsadmin.banners.index',
            'store' => 'cmsadmin.banners.store',
            'show' => 'cmsadmin.banners.show',
            'update' => 'cmsadmin.banners.update',
            'destroy' => 'cmsadmin.banners.destroy',
            'create' => 'cmsadmin.banners.create',
            'edit' => 'cmsadmin.banners.edit',
        ]);
    Route::post('cmsadmin/banner/{id}/remove-image', [BannerController::class, 'removeImage'])->name('cmsadmin.banners.removeImage');
    Route::post('cmsadmin/banner/toggle-publish', [BannerController::class, 'togglePublish'])->name('cmsadmin.banners.togglePublish');
    Route::post('cmsadmin/banner/toggle-reserved', [BannerController::class, 'toggleReserved'])->name('cmsadmin.banners.toggleReserved');
    Route::post('cmsadmin/banner/reorder', [BannerController::class, 'reorder'])->name('cmsadmin.banners.reorder');
    Route::get('cmsadmin/banner/{id}/image-edit/{field}', [BannerController::class, 'imageEdit'])->name('cmsadmin.banners.imageEdit');
    Route::patch('cmsadmin/banner/{id}/image-update/{field}', [BannerController::class, 'imageUpdate'])->name('cmsadmin.banners.imageUpdate');

    // video-galleries
    Route::resource('cmsadmin/video-album/{videoAlbum}/gallery', VideoGalleryController::class)
        ->names([
            'index' => 'cmsadmin.videoGalleries.index',
            'store' => 'cmsadmin.videoGalleries.store',
            'show' => 'cmsadmin.videoGalleries.show',
            'update' => 'cmsadmin.videoGalleries.update',
            'destroy' => 'cmsadmin.videoGalleries.destroy',
            'create' => 'cmsadmin.videoGalleries.create',
            'edit' => 'cmsadmin.videoGalleries.edit',
        ]);
    Route::post('cmsadmin/video-album/{videoAlbum}/gallery/remove-image', [VideoGalleryController::class, 'removeImage'])->name('cmsadmin.videoGalleries.removeImage');
    Route::post('cmsadmin/video-album/{videoAlbum}/gallery/toggle-publish', [VideoGalleryController::class, 'togglePublish'])->name('cmsadmin.videoGalleries.togglePublish');
    Route::post('cmsadmin/video-album/{videoAlbum}/gallery/toggle-reserved', [VideoGalleryController::class, 'toggleReserved'])->name('cmsadmin.videoGalleries.toggleReserved');
    Route::get('cmsadmin/video-album/{id}/image-edit/{field}', [VideoGalleryController::class, 'imageEdit'])->name('cmsadmin.videoGalleries.imageEdit');
    Route::patch('cmsadmin/video-album/{id}/image-update/{field}', [VideoGalleryController::class, 'imageUpdate'])->name('cmsadmin.videoGalleries.imageUpdate');

    // testimonial
    Route::get('cmsadmin/testimonial/trash-list', [TestimonialController::class, 'trashList'])->name('cmsadmin.testimonials.trashList');
    Route::post('cmsadmin/testimonial/restore/{id}', [TestimonialController::class, 'restore'])->name('cmsadmin.testimonials.restore');
    Route::delete('cmsadmin/testimonial/permanent-destroy/{id}', [TestimonialController::class, 'permanentDestroy'])->name('cmsadmin.testimonials.permanentDestroy');
    Route::resource('cmsadmin/testimonial', TestimonialController::class)
        ->names([
            'index' => 'cmsadmin.testimonials.index',
            'store' => 'cmsadmin.testimonials.store',
            'show' => 'cmsadmin.testimonials.show',
            'update' => 'cmsadmin.testimonials.update',
            'destroy' => 'cmsadmin.testimonials.destroy',
            'create' => 'cmsadmin.testimonials.create',
            'edit' => 'cmsadmin.testimonials.edit',
        ]);
    Route::post('cmsadmin/testimonial/{id}/remove-image', [TestimonialController::class, 'removeImage'])->name('cmsadmin.testimonials.removeImage');
    Route::post('cmsadmin/testimonial/toggle-publish', [TestimonialController::class, 'togglePublish'])->name('cmsadmin.testimonials.togglePublish');
    Route::post('cmsadmin/testimonial/toggle-reserved', [TestimonialController::class, 'toggleReserved'])->name('cmsadmin.testimonials.toggleReserved');
    Route::post('cmsadmin/testimonial/reorder', [TestimonialController::class, 'reorder'])->name('cmsadmin.testimonials.reorder');
    Route::get('cmsadmin/testimonial/{id}/image-edit/{field}', [TestimonialController::class, 'imageEdit'])->name('cmsadmin.testimonials.imageEdit');
    Route::patch('cmsadmin/testimonial/{id}/image-update/{field}', [TestimonialController::class, 'imageUpdate'])->name('cmsadmin.testimonials.imageUpdate');

    // contact
    Route::resource('cmsadmin/contact', ContactController::class)
        ->names([
            'index' => 'cmsadmin.contacts.index',
            'show' => 'cmsadmin.contacts.show',
        ])->except(['create', 'edit', 'update', 'store', 'destroy']);
    Route::post('cmsadmin/contact/{id}/load-resend-mail', [ContactController::class, 'loadResendMail'])->name('cmsadmin.contacts.loadResendMail');
    Route::post('cmsadmin/contact/{id}/resend-mail', [ContactController::class, 'resendMail'])->name('cmsadmin.contacts.resendMail');
    Route::get('cmsadmin/contact/export-pdf/{id}', [ContactController::class, 'exportPdf'])->name('cmsadmin.contacts.exportPdf');

    // faq-category
    Route::get('cmsadmin/faq-category/trash-list', [FaqCategoryController::class, 'trashList'])->name('cmsadmin.faqCategories.trashList');
    Route::post('cmsadmin/faq-category/restore/{id}', [FaqCategoryController::class, 'restore'])->name('cmsadmin.faqCategories.restore');
    Route::delete('cmsadmin/faq-category/permanent-destroy/{id}', [FaqCategoryController::class, 'permanentDestroy'])->name('cmsadmin.faqCategories.permanentDestroy');
    Route::resource('cmsadmin/faq-category', FaqCategoryController::class)
        ->names([
            'index' => 'cmsadmin.faqCategories.index',
            'store' => 'cmsadmin.faqCategories.store',
            'show' => 'cmsadmin.faqCategories.show',
            'update' => 'cmsadmin.faqCategories.update',
            'destroy' => 'cmsadmin.faqCategories.destroy',
            'create' => 'cmsadmin.faqCategories.create',
            'edit' => 'cmsadmin.faqCategories.edit',
        ]);
    Route::post('cmsadmin/faq-category/{id}/remove-image', [FaqCategoryController::class, 'removeImage'])->name('cmsadmin.faqCategories.removeImage');
    Route::post('cmsadmin/faq-category/toggle-publish', [FaqCategoryController::class, 'togglePublish'])->name('cmsadmin.faqCategories.togglePublish');
    Route::post('cmsadmin/faq-category/toggle-reserved', [FaqCategoryController::class, 'toggleReserved'])->name('cmsadmin.faqCategories.toggleReserved');
    Route::post('cmsadmin/faq-category/reorder', [FaqCategoryController::class, 'reorder'])->name('cmsadmin.faqCategories.reorder');
    Route::get('cmsadmin/faq-category/{id}/image-edit/{field}', [FaqCategoryController::class, 'imageEdit'])->name('cmsadmin.faqCategories.imageEdit');
    Route::patch('cmsadmin/faq-category/{id}/image-update/{field}', [FaqCategoryController::class, 'imageUpdate'])->name('cmsadmin.faqCategories.imageUpdate');

    // faq
    Route::get('cmsadmin/faq/trash-list', [FaqController::class, 'trashList'])->name('cmsadmin.faqs.trashList');
    Route::post('cmsadmin/faq/restore/{id}', [FaqController::class, 'restore'])->name('cmsadmin.faqs.restore');
    Route::delete('cmsadmin/faq/permanent-destroy/{id}', [FaqController::class, 'permanentDestroy'])->name('cmsadmin.faqs.permanentDestroy');
    Route::resource('cmsadmin/faq', FaqController::class)
        ->names([
            'index' => 'cmsadmin.faqs.index',
            'store' => 'cmsadmin.faqs.store',
            'show' => 'cmsadmin.faqs.show',
            'update' => 'cmsadmin.faqs.update',
            'destroy' => 'cmsadmin.faqs.destroy',
            'create' => 'cmsadmin.faqs.create',
            'edit' => 'cmsadmin.faqs.edit',
        ]);
    Route::post('cmsadmin/faq/toggle-publish', [FaqController::class, 'togglePublish'])->name('cmsadmin.faqs.togglePublish');
    Route::post('cmsadmin/faq/toggle-reserved', [FaqController::class, 'toggleReserved'])->name('cmsadmin.faqs.toggleReserved');
    Route::post('cmsadmin/faq/reorder', [FaqController::class, 'reorder'])->name('cmsadmin.faqs.reorder');

    // resource
    Route::get('cmsadmin/resource/trash-list', [ResourceController::class, 'trashList'])->name('cmsadmin.resources.trashList');
    Route::post('cmsadmin/resource/restore/{id}', [ResourceController::class, 'restore'])->name('cmsadmin.resources.restore');
    Route::delete('cmsadmin/resource/permanent-destroy/{id}', [ResourceController::class, 'permanentDestroy'])->name('cmsadmin.resources.permanentDestroy');
    Route::resource('cmsadmin/resource', ResourceController::class)
        ->names([
            'index' => 'cmsadmin.resources.index',
            'store' => 'cmsadmin.resources.store',
            'show' => 'cmsadmin.resources.show',
            'update' => 'cmsadmin.resources.update',
            'destroy' => 'cmsadmin.resources.destroy',
            'create' => 'cmsadmin.resources.create',
            'edit' => 'cmsadmin.resources.edit',
        ]);
    Route::post('cmsadmin/resource/toggle-publish', [ResourceController::class, 'togglePublish'])->name('cmsadmin.resources.togglePublish');
    Route::post('cmsadmin/resource/toggle-reserved', [ResourceController::class, 'toggleReserved'])->name('cmsadmin.resources.toggleReserved');

    // SEO
    Route::get('cmsadmin/seo/trash-list', [SeoController::class, 'trashList'])->name('cmsadmin.seos.trashList');
    Route::post('cmsadmin/seo/restore/{id}', [SeoController::class, 'restore'])->name('cmsadmin.seos.restore');
    Route::delete('cmsadmin/seo/permanent-destroy/{id}', [SeoController::class, 'permanentDestroy'])->name('cmsadmin.seos.permanentDestroy');
    Route::resource('cmsadmin/seo', SeoController::class)
        ->names([
            'index' => 'cmsadmin.seos.index',
            'store' => 'cmsadmin.seos.store',
            'show' => 'cmsadmin.seos.show',
            'update' => 'cmsadmin.seos.update',
            'destroy' => 'cmsadmin.seos.destroy',
            'create' => 'cmsadmin.seos.create',
            'edit' => 'cmsadmin.seos.edit',
        ]);
    Route::post('cmsadmin/seo/toggle-publish', [SeoController::class, 'togglePublish'])->name('cmsadmin.seos.togglePublish');

    // csp_header
    Route::get('cmsadmin/csp-header/regenerate', [CspHeaderController::class, 'regenerate'])->name('cmsadmin.cspHeaders.regenerate');
    Route::post('cmsadmin/csp-header/toggle-publish', [CspHeaderController::class, 'togglePublish'])->name('cmsadmin.cspHeaders.togglePublish');
    Route::resource('cmsadmin/csp-header', CspHeaderController::class)
        ->names([
            'index' => 'cmsadmin.cspHeaders.index',
            'update' => 'cmsadmin.cspHeaders.update',
            'edit' => 'cmsadmin.cspHeaders.edit',
        ])->except(['create', 'store', 'destroy', 'show']);

});
