<?php

/**
 * breadcrumbs/cms-admin.php
 */
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Modules\CmsAdmin\app\Models\Album;
use Modules\CmsAdmin\app\Models\Banner;
use Modules\CmsAdmin\app\Models\Block;
use Modules\CmsAdmin\app\Models\Blog;
use Modules\CmsAdmin\app\Models\BlogCategory;
use Modules\CmsAdmin\app\Models\Contact;
use Modules\CmsAdmin\app\Models\Faq;
use Modules\CmsAdmin\app\Models\FaqCategory;
use Modules\CmsAdmin\app\Models\Menu;
use Modules\CmsAdmin\app\Models\News;
use Modules\CmsAdmin\app\Models\NewsCategory;
use Modules\CmsAdmin\app\Models\Page;
use Modules\CmsAdmin\app\Models\Post;
use Modules\CmsAdmin\app\Models\PostCategory;
use Modules\CmsAdmin\app\Models\Resource;
use Modules\CmsAdmin\app\Models\Seo;
use Modules\CmsAdmin\app\Models\Testimonial;
use Modules\CmsAdmin\app\Models\VideoAlbum;
use Modules\CmsAdmin\app\Models\VideoGallery;

// SiteSetup >> Menu
Breadcrumbs::for('menu', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/menus.plural'), route('cmsadmin.menus.index'));
});

// SiteSetup >> Menu >> Trash
Breadcrumbs::for('menu_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('menu');
    $trail->push(__('common::crud.trash'), route('cmsadmin.menus.trashList'));
});

// SiteSetup >> Menu >> Trash >> Name
Breadcrumbs::for('menu_trash_detail', function (BreadcrumbTrail $trail, Menu $menu) {
    $trail->parent('menu_trash');
    $trail->push($menu->title, route('cmsadmin.menus.show', $menu));
});

// SiteSetup >> Menu >> Create
Breadcrumbs::for('menu_create', function (BreadcrumbTrail $trail) {
    $trail->parent('menu');
    $trail->push(__('common::crud.create'), route('cmsadmin.menus.create'));
});

// SiteSetup >> Menu >> Name
Breadcrumbs::for('menu_detail', function (BreadcrumbTrail $trail, Menu $menu) {
    $trail->parent('menu');
    $trail->push($menu->title, route('cmsadmin.menus.show', $menu));
});

// SiteSetup >> Menu >> Name >> Edit
Breadcrumbs::for('menu_edit', function (BreadcrumbTrail $trail, Menu $menu) {
    $trail->parent('menu_detail', $menu);
    $trail->push(__('common::crud.edit'), route('cmsadmin.menus.edit', $menu));
});

// News Management >> NewsCategory
Breadcrumbs::for('newsCategory', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/news_categories.singular'), route('cmsadmin.newsCategories.index'));
});

// News Management>> NewsCategory >> Trash
Breadcrumbs::for('newsCategory_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('newsCategory');
    $trail->push(__('common::crud.trash'), route('cmsadmin.newsCategories.trashList'));
});

// News Management>> NewsCategory >> Trash >> Name
Breadcrumbs::for('newsCategory_trash_detail', function (BreadcrumbTrail $trail, NewsCategory $newsCategory) {
    $trail->parent('newsCategory_trash');
    $trail->push($newsCategory->category_name, route('cmsadmin.newsCategories.show', $newsCategory));
});

// News Management >> NewsCategory >> Create
Breadcrumbs::for('news_category_create', function (BreadcrumbTrail $trail) {
    $trail->parent('newsCategory');
    $trail->push(__('common::crud.create'), route('cmsadmin.newsCategories.create'));
});

// News Management >> NewsCategory >> Name
Breadcrumbs::for('news_category_detail', function (BreadcrumbTrail $trail, NewsCategory $newsCategory) {
    $trail->parent('newsCategory');
    $trail->push($newsCategory->category_name, route('cmsadmin.newsCategories.show', $newsCategory));
});

// News Management >> NewsCategory >> Name >> Edit
Breadcrumbs::for('news_category_edit', function (BreadcrumbTrail $trail, NewsCategory $newsCategory) {
    $trail->parent('news_category_detail', $newsCategory);
    $trail->push(__('common::crud.edit'), route('cmsadmin.newsCategories.edit', $newsCategory));
});

// News Management >> News
Breadcrumbs::for('news', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/news.singular'), route('cmsadmin.news.index'));
});

// News Management >> News >> Trash
Breadcrumbs::for('news_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('news');
    $trail->push(__('common::crud.trash'), route('cmsadmin.news.trashList'));
});

// News Management >> News >> Trash >> Name
Breadcrumbs::for('news_trash_detail', function (BreadcrumbTrail $trail, News $news) {
    $trail->parent('news_trash');
    $trail->push($news->news_title, route('cmsadmin.news.show', $news));
});

// News Management >> News >> Create
Breadcrumbs::for('news_create', function (BreadcrumbTrail $trail) {
    $trail->parent('news');
    $trail->push(__('common::crud.create'), route('cmsadmin.news.create'));
});

// News Management >> News >> Name
Breadcrumbs::for('news_detail', function (BreadcrumbTrail $trail, News $news) {
    $trail->parent('news');
    $trail->push($news->news_title, route('cmsadmin.news.show', $news));
});

// News Management >> News >> Name >> Edit
Breadcrumbs::for('news_edit', function (BreadcrumbTrail $trail, News $news) {
    $trail->parent('news_detail', $news);
    $trail->push(__('common::crud.edit'), route('cmsadmin.news.edit', $news));
});

// News Management >> News >> Name >> MultiData
Breadcrumbs::for('news_multidata', function (BreadcrumbTrail $trail, News $news) {
    $trail->parent('news_detail', $news);
    $trail->push(__('common::multidata.name'), route('cmsadmin.news.multidata', $news));
});

// News Management >>  News >> Name >> NewsDetail
Breadcrumbs::for('news_multidata_details', function (BreadcrumbTrail $trail, News $news) {
    $trail->parent('news_detail', $news);
    $trail->push(__('cmsadmin::models/news.news_multidata.singular'), route('cmsadmin.newsDetails.index', $news));
});

// Post Management >> PostCategory
Breadcrumbs::for('postCategory', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/post_categories.singular'), route('cmsadmin.postCategories.index'));
});

// Post Management >>PostCategory >> Trash
Breadcrumbs::for('post_category_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('postCategory');
    $trail->push(__('common::crud.trash'), route('cmsadmin.postCategories.trashList'));
});

// Post Management >> PostCategory >> Trash >> Name
Breadcrumbs::for('post_category_trash_detail', function (BreadcrumbTrail $trail, PostCategory $postCategory) {
    $trail->parent('post_category_trash');
    $trail->push($postCategory->category_name, route('cmsadmin.postCategories.show', $postCategory));
});

// Post Management >> PostCategory >> Create
Breadcrumbs::for('post_category_create', function (BreadcrumbTrail $trail) {
    $trail->parent('postCategory');
    $trail->push(__('common::crud.create'), route('cmsadmin.postCategories.create'));
});

// Post Management >> PostCategory >> Name
Breadcrumbs::for('post_category_detail', function (BreadcrumbTrail $trail, PostCategory $postCategory) {
    $trail->parent('postCategory');
    $trail->push($postCategory->category_name, route('cmsadmin.postCategories.show', $postCategory));
});

// Post Management >> PostCategory >> Name >> Edit
Breadcrumbs::for('post_category_edit', function (BreadcrumbTrail $trail, PostCategory $postCategory) {
    $trail->parent('post_category_detail', $postCategory);
    $trail->push(__('common::crud.edit'), route('cmsadmin.postCategories.edit', $postCategory));
});

// Post Management >> Post
Breadcrumbs::for('post', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/posts.singular'), route('cmsadmin.posts.index'));
});

// Post Management >>Post >> Trash
Breadcrumbs::for('post_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('post');
    $trail->push(__('common::crud.trash'), route('cmsadmin.posts.trashList'));
});

// Post Management >> Post >> Trash >> Name
Breadcrumbs::for('post_trash_detail', function (BreadcrumbTrail $trail, Post $post) {
    $trail->parent('post_trash');
    $trail->push($post->post_title, route('cmsadmin.posts.show', $post));
});

// Post Management >> Post >> Create
Breadcrumbs::for('post_create', function (BreadcrumbTrail $trail) {
    $trail->parent('post');
    $trail->push(__('common::crud.create'), route('cmsadmin.posts.create'));
});

// Post Management >> Post >> Name
Breadcrumbs::for('post_detail', function (BreadcrumbTrail $trail, Post $post) {
    $trail->parent('post');
    $trail->push($post->post_title, route('cmsadmin.posts.show', $post));
});

// Post Management >> Post >> Name >> Edit
Breadcrumbs::for('post_edit', function (BreadcrumbTrail $trail, Post $post) {
    $trail->parent('post_detail', $post);
    $trail->push(__('common::crud.edit'), route('cmsadmin.posts.edit', $post));
});

// Post Management >> Post >> Name >> MultiData
Breadcrumbs::for('post_multidata', function (BreadcrumbTrail $trail, Post $post) {
    $trail->parent('post_detail', $post);
    $trail->push(__('common::multidata.name'), route('cmsadmin.posts.multidata', $post));
});

// SiteSetup >>  post >> Name >> PostDetail
Breadcrumbs::for('post_multidata_details', function (BreadcrumbTrail $trail, Post $post) {
    $trail->parent('post_detail', $post);
    $trail->push(__('cmsadmin::models/posts.post_multidata.singular'), route('cmsadmin.postDetails.index', $post));
});

// Image Album
Breadcrumbs::for('album', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/albums.singular'), route('cmsadmin.albums.index'));
});

// Image Album >> Trash
Breadcrumbs::for('album_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('album');
    $trail->push(__('common::crud.trash'), route('cmsadmin.albums.trashList'));
});

// Image Album >> Trash >> Name
Breadcrumbs::for('album_trash_detail', function (BreadcrumbTrail $trail, Album $album) {
    $trail->parent('album_trash');
    $trail->push($album->title, route('cmsadmin.albums.show', $album));
});

// Image Album >> Create
Breadcrumbs::for('album_create', function (BreadcrumbTrail $trail) {
    $trail->parent('album');
    $trail->push(__('common::crud.create'), route('cmsadmin.albums.create'));
});

// Image Album >> Name
Breadcrumbs::for('album_detail', function (BreadcrumbTrail $trail, Album $album) {
    $trail->parent('album');
    $trail->push($album->title, route('cmsadmin.albums.show', $album));
});

// Image Album >> Name >> Edit
Breadcrumbs::for('album_edit', function (BreadcrumbTrail $trail, Album $album) {
    $trail->parent('album_detail', $album);
    $trail->push(__('common::crud.edit'), route('cmsadmin.albums.edit', $album));
});

// Image Album >> Name >> Gallery
Breadcrumbs::for('album_gallery', function (BreadcrumbTrail $trail, Album $album) {
    $trail->parent('album_detail', $album);
    $trail->push(__('cmsadmin::models/albums.gallery'), route('cmsadmin.albums.edit', $album));
});

// Blog Management >> BlogCategory
Breadcrumbs::for('blogCategory', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/blog_categories.singular'), route('cmsadmin.blogCategories.index'));
});

Breadcrumbs::for('blog_category_create', function (BreadcrumbTrail $trail) {
    $trail->parent('blogCategory');
    $trail->push(__('common::crud.create'), route('cmsadmin.blogCategories.create'));
});

Breadcrumbs::for('blog_category_detail', function (BreadcrumbTrail $trail, BlogCategory $blogCategory) {
    $trail->parent('blogCategory');
    $trail->push($blogCategory->cat_title, route('cmsadmin.blogCategories.show', $blogCategory));
});

// Blog Management >> BlogCategory >> Name >> Edit
Breadcrumbs::for('blog_category_edit', function (BreadcrumbTrail $trail, BlogCategory $blogCategory) {
    $trail->parent('blog_category_detail', $blogCategory);
    $trail->push(__('common::crud.edit'), route('cmsadmin.blogCategories.edit', $blogCategory));
});

// Blog Management  >> BlogCategory>> Trash
Breadcrumbs::for('blogCategory_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('blogCategory');
    $trail->push(__('common::crud.trash'), route('cmsadmin.blogCategories.trashList'));
});

// Blog Management >> BlogCategory >> Trash >> Name
Breadcrumbs::for('blog_category_trash_detail', function (BreadcrumbTrail $trail, BlogCategory $blogCategory) {
    $trail->parent('blogCategory_trash');
    $trail->push($blogCategory->cat_title, route('cmsadmin.blogCategories.show', $blogCategory));
});

// Blog Management >> Blog
Breadcrumbs::for('blog', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/blogs.singular'), route('cmsadmin.blogs.index'));
});

// Blog Management  >> Blog>> Trash
Breadcrumbs::for('blog_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('blog');
    $trail->push(__('common::crud.trash'), route('cmsadmin.blogs.trashList'));
});

// Blog Management >> Blog >> Create
Breadcrumbs::for('blog_create', function (BreadcrumbTrail $trail) {
    $trail->parent('blog');
    $trail->push(__('common::crud.create'), route('cmsadmin.blogs.create'));
});

// Blog Management >> Blog >> Name
Breadcrumbs::for('blog_detail', function (BreadcrumbTrail $trail, Blog $blog) {
    $trail->parent('blog');
    $trail->push($blog->title, route('cmsadmin.blogs.show', $blog));
});

// Blog Management >> Blog >> Name >> Edit
Breadcrumbs::for('blog_edit', function (BreadcrumbTrail $trail, Blog $blog) {
    $trail->parent('blog_detail', $blog);
    $trail->push(__('common::crud.edit'), route('cmsadmin.blogs.edit', $blog));
});

// Blog Management >> Blog >> Name >> MultiData
Breadcrumbs::for('blog_multidata', function (BreadcrumbTrail $trail, Blog $blog) {
    $trail->parent('blog_detail', $blog);
    $trail->push(__('common::multidata.name'), route('cmsadmin.blogs.multidata', $blog));
});

// Blog Management >> Blog >> Trash >> Name
Breadcrumbs::for('blog_trash_detail', function (BreadcrumbTrail $trail, Blog $Blog) {
    $trail->parent('blog_trash');
    $trail->push($Blog->title, route('cmsadmin.blogs.show', $Blog));
});
// SiteSetup >>  blog >> Name >> blogDetail
Breadcrumbs::for('blog_multidata_details', function (BreadcrumbTrail $trail, Blog $Blog) {
    $trail->parent('blog_detail', $Blog);
    $trail->push(__('cmsadmin::models/blogs.blog_multidata.singular'), route('cmsadmin.blogDetails.index', $Blog));
});

// Gallery Management >> Video Album
Breadcrumbs::for('video_album', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/video_albums.singular'), route('cmsadmin.videoAlbums.index'));
});

// Gallery Management >> Video Album >>Trash
Breadcrumbs::for('video_album_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('video_album');
    $trail->push(__('common::crud.trash'), route('cmsadmin.videoAlbums.trashList'));
});

// Gallery Management >> Video Album >>Trash >> Name
Breadcrumbs::for('video_album_trash_detail', function (BreadcrumbTrail $trail, VideoAlbum $videoAlbum) {
    $trail->parent('video_album_trash');
    $trail->push($videoAlbum->album_name, route('cmsadmin.videoAlbums.show', $videoAlbum));
});

// Gallery Management >> Video Album >> Create
Breadcrumbs::for('video_album_create', function (BreadcrumbTrail $trail) {
    $trail->parent('video_album');
    $trail->push(__('common::crud.create'), route('cmsadmin.videoAlbums.create'));
});

// Gallery Management >> Video Album >> Name
Breadcrumbs::for('video_album_detail', function (BreadcrumbTrail $trail, VideoAlbum $videoAlbum) {
    $trail->parent('video_album');
    $trail->push($videoAlbum->album_name, route('cmsadmin.videoAlbums.show', $videoAlbum));
});

// Gallery Management >> Video Album >> Name >> Edit
Breadcrumbs::for('video_album_edit', function (BreadcrumbTrail $trail, VideoAlbum $videoAlbum) {
    $trail->parent('video_album_detail', $videoAlbum);
    $trail->push(__('common::crud.edit'), route('cmsadmin.videoAlbums.edit', $videoAlbum));
});

// Gallery Management >> Video Album >> Name >> Gallery list
Breadcrumbs::for('video_gallery', function (BreadcrumbTrail $trail, VideoAlbum $videoAlbum) {
    $trail->parent('video_album_detail', $videoAlbum);
    $trail->push(__('cmsadmin::models/video_galleries.singular'), route('cmsadmin.videoGalleries.index', $videoAlbum));
});

// Gallery Management >> Video Album >> Name >> Gallery  >> Create
Breadcrumbs::for('video_gallery_create', function (BreadcrumbTrail $trail, VideoAlbum $videoAlbum) {
    $trail->parent('video_gallery', $videoAlbum);
    $trail->push(__('common::crud.create'), route('cmsadmin.videoGalleries.create', $videoAlbum));
});

// Gallery Management >> Video Album >> Name >> Gallery >> Name
Breadcrumbs::for('video_gallery_detail', function (BreadcrumbTrail $trail, VideoAlbum $videoAlbum, VideoGallery $videoGallery) {
    $trail->parent('video_gallery', $videoAlbum);
    $trail->push($videoGallery->caption, route('cmsadmin.videoGalleries.show', ['videoAlbum' => $videoAlbum, 'gallery' => $videoGallery]));
});

// Gallery Management >> Video Album >> Name >> Gallery >> Name >> Edit
Breadcrumbs::for('video_gallery_edit', function (BreadcrumbTrail $trail, VideoAlbum $videoAlbum, VideoGallery $videoGallery) {
    $trail->parent('video_gallery_detail', $videoAlbum, $videoGallery);
    $trail->push(__('common::crud.edit'), route('cmsadmin.videoGalleries.edit', ['videoAlbum' => $videoAlbum, 'gallery' => $videoGallery]));
});

// Testimonial
Breadcrumbs::for('testimonial', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/testimonials.plural'), route('cmsadmin.testimonials.index'));
});

// Testimonial >> Trash
Breadcrumbs::for('testimonial_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('testimonial');
    $trail->push(__('common::crud.trash'), route('cmsadmin.testimonials.trashList'));
});

// Testimonial >> Trash >> Name
Breadcrumbs::for('testimonial_trash_detail', function (BreadcrumbTrail $trail, Testimonial $testimonial) {
    $trail->parent('testimonial_trash');
    $trail->push($testimonial->tm_name, route('cmsadmin.testimonials.show', $testimonial));
});

// Testimonial >> Create
Breadcrumbs::for('testimonial_create', function (BreadcrumbTrail $trail) {
    $trail->parent('testimonial');
    $trail->push(__('common::crud.create'), route('cmsadmin.testimonials.create'));
});

// Testimonial >> Name
Breadcrumbs::for('testimonial_detail', function (BreadcrumbTrail $trail, Testimonial $testimonial) {
    $trail->parent('testimonial');
    $trail->push($testimonial->tm_name, route('cmsadmin.testimonials.show', $testimonial));
});

// Testimonial >> Name >> Edit
Breadcrumbs::for('testimonial_edit', function (BreadcrumbTrail $trail, Testimonial $testimonial) {
    $trail->parent('testimonial_detail', $testimonial);
    $trail->push(__('common::crud.edit'), route('cmsadmin.testimonials.edit', $testimonial));
});

// SEO
Breadcrumbs::for('seo', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/seos.singular'), route('cmsadmin.seos.index'));
});

// SEO >> Trash
Breadcrumbs::for('seo_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('seo');
    $trail->push(__('common::crud.trash'), route('cmsadmin.seos.trashList'));
});

// SEO >> Trash >> Name
Breadcrumbs::for('seo_trash_detail', function (BreadcrumbTrail $trail, Seo $seo) {
    $trail->parent('seo_trash');
    $trail->push($seo->module_name, route('cmsadmin.seos.show', $seo));
});

// SEO >> Create
Breadcrumbs::for('seo_create', function (BreadcrumbTrail $trail) {
    $trail->parent('seo');
    $trail->push(__('common::crud.create'), route('cmsadmin.seos.create'));
});

// SEO >> Name
Breadcrumbs::for('seo_detail', function (BreadcrumbTrail $trail, Seo $seo) {
    $trail->parent('seo');
    $trail->push($seo->module_name, route('cmsadmin.seos.show', $seo));
});

// SEO >> Name >> Edit
Breadcrumbs::for('seo_edit', function (BreadcrumbTrail $trail, Seo $seo) {
    $trail->parent('seo_detail', $seo);
    $trail->push(__('common::crud.edit'), route('cmsadmin.seos.edit', $seo));
});

// Contact
Breadcrumbs::for('contact', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/contacts.singular'), route('cmsadmin.contacts.index'));
});

// Contact >> Name
Breadcrumbs::for('contact_detail', function (BreadcrumbTrail $trail, Contact $contact) {
    $trail->parent('contact');
    $trail->push($contact->from_name, route('cmsadmin.contacts.show', $contact));
});

// FAQ MANAGEMENT >> FAQ Category
Breadcrumbs::for('faqCategory', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/faq_categories.plural'), route('cmsadmin.faqCategories.index'));
});

// FAQ Management >> FAQ Category >> Trash
Breadcrumbs::for('faqCategory_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('faqCategory');
    $trail->push(__('common::crud.trash'), route('cmsadmin.faqCategories.trashList'));
});

// FAQ Management >> FAQ Category >> Trash >> Name
Breadcrumbs::for('faqCategory_trash_detail', function (BreadcrumbTrail $trail, FaqCategory $faqCategory) {
    $trail->parent('faqCategory_trash');
    $trail->push($faqCategory->faq_cat_name, route('cmsadmin.faqCategories.show', $faqCategory));
});

// FAQ Management >> FAQ Category >> Create
Breadcrumbs::for('faqCategory_create', function (BreadcrumbTrail $trail) {
    $trail->parent('faqCategory');
    $trail->push(__('common::crud.create'), route('cmsadmin.faqCategories.create'));
});

// FAQ Management >> FAQ Category >> Name
Breadcrumbs::for('faqCategory_detail', function (BreadcrumbTrail $trail, FaqCategory $faqCategory) {
    $trail->parent('faqCategory');
    $trail->push($faqCategory->faq_cat_name, route('cmsadmin.faqCategories.show', $faqCategory));
});

// FAQ Management >> FAQ Category >> Name >> Edit
Breadcrumbs::for('faqCategory_edit', function (BreadcrumbTrail $trail, FaqCategory $faqCategory) {
    $trail->parent('faqCategory_detail', $faqCategory);
    $trail->push(__('common::crud.edit'), route('cmsadmin.faqCategories.edit', $faqCategory));
});

// Resource
Breadcrumbs::for('resource', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/resources.singular'), route('cmsadmin.resources.index'));
});

// Resource>> Trash
Breadcrumbs::for('resource_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('resource');
    $trail->push(__('common::crud.trash'), route('cmsadmin.resources.trashList'));
});

// Resource >> Create
Breadcrumbs::for('resource_create', function (BreadcrumbTrail $trail) {
    $trail->parent('resource');
    $trail->push(__('common::crud.create'), route('cmsadmin.resources.create'));
});

// Resource >> Name
Breadcrumbs::for('resource_detail', function (BreadcrumbTrail $trail, Resource $resource) {
    $trail->parent('resource');
    $trail->push($resource->display_name, route('cmsadmin.resources.show', $resource));
});

// Resource >> Name >> Edit
Breadcrumbs::for('resource_edit', function (BreadcrumbTrail $trail, Resource $resource) {
    $trail->parent('resource_detail', $resource);
    $trail->push(__('common::crud.edit'), route('cmsadmin.resources.edit', $resource));
});

// FAQ MANAGEMENT >> FAQ
Breadcrumbs::for('faq', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/faqs.plural'), route('cmsadmin.faqs.index'));
});

// FAQ Management >> FAQ  >> Trash
Breadcrumbs::for('faq_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('faq');
    $trail->push(__('common::crud.trash'), route('cmsadmin.faqs.trashList'));
});

// FAQ Management >> FAQ  >> Trash >> Name
Breadcrumbs::for('faq_trash_detail', function (BreadcrumbTrail $trail, Faq $faq) {
    $trail->parent('faq_trash');
    $trail->push($faq->question, route('cmsadmin.faqs.show', $faq));
});

// FAQ Management >> FAQ  >> Create
Breadcrumbs::for('faq_create', function (BreadcrumbTrail $trail) {
    $trail->parent('faq');
    $trail->push(__('common::crud.create'), route('cmsadmin.faqs.create'));
});

// FAQ Management >> FAQ  >> Name
Breadcrumbs::for('faq_detail', function (BreadcrumbTrail $trail, Faq $faq) {
    $trail->parent('faq');
    $trail->push($faq->question, route('cmsadmin.faqs.show', $faq));
});

// FAQ Management >> FAQ  >> Name >> Edit
Breadcrumbs::for('faq_edit', function (BreadcrumbTrail $trail, Faq $faq) {
    $trail->parent('faq_detail', $faq);
    $trail->push(__('common::crud.edit'), route('cmsadmin.faqs.edit', $faq));
});

// SiteSetup >> Block
Breadcrumbs::for('block', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/blocks.plural'), route('cmsadmin.blocks.index'));
});

// SiteSetup >> Block >> Trash
Breadcrumbs::for('block_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('block');
    $trail->push(__('common::crud.trash'), route('cmsadmin.blocks.trashList'));
});

// SiteSetup >> Block >> Trash >> Name
Breadcrumbs::for('block_trash_detail', function (BreadcrumbTrail $trail, Block $block) {
    $trail->parent('block_trash');
    $trail->push($block->block_name, route('cmsadmin.blocks.show', $block));
});

// SiteSetup >> Block >> Create
Breadcrumbs::for('block_create', function (BreadcrumbTrail $trail) {
    $trail->parent('block');
    $trail->push(__('common::crud.create'), route('cmsadmin.blocks.create'));
});

// SiteSetup >> Block >> Name
Breadcrumbs::for('block_detail', function (BreadcrumbTrail $trail, Block $block) {
    $trail->parent('block');
    $trail->push($block->block_name, route('cmsadmin.blocks.show', $block));
});

// SiteSetup >> Block >> Name >> Edit
Breadcrumbs::for('block_edit', function (BreadcrumbTrail $trail, Block $block) {
    $trail->parent('block_detail', $block);
    $trail->push(__('common::crud.edit'), route('cmsadmin.blocks.edit', $block));
});

// SiteSetup >> Page
Breadcrumbs::for('page', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/pages.plural'), route('cmsadmin.pages.index'));
});

// SiteSetup >> Page >> Trash
Breadcrumbs::for('page_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('page');
    $trail->push(__('common::crud.trash'), route('cmsadmin.pages.trashList'));
});

// SiteSetup >> Page >> Trash >> Name
Breadcrumbs::for('page_trash_detail', function (BreadcrumbTrail $trail, Page $page) {
    $trail->parent('page_trash');
    $trail->push($page->page_title, route('cmsadmin.pages.show', $page));
});

// SiteSetup >> Page >> Create
Breadcrumbs::for('page_create', function (BreadcrumbTrail $trail) {
    $trail->parent('page');
    $trail->push(__('common::crud.create'), route('cmsadmin.pages.create'));
});

// SiteSetup >> Page >> Name
Breadcrumbs::for('page_detail', function (BreadcrumbTrail $trail, Page $page) {
    $trail->parent('page');
    $trail->push($page->page_title, route('cmsadmin.pages.show', $page));
});

// SiteSetup >> Page >> Name >> Edit
Breadcrumbs::for('page_edit', function (BreadcrumbTrail $trail, Page $page) {
    $trail->parent('page_detail', $page);
    $trail->push(__('common::crud.edit'), route('cmsadmin.pages.edit', $page));
});
// SiteSetup >> Page >> Clone
Breadcrumbs::for('page_clone', function (BreadcrumbTrail $trail) {
    $trail->parent('page');
    $trail->push(__('common::crud.clone'));
});

// SiteSetup >> Page >> Name >> MultiData
Breadcrumbs::for('page_multidata', function (BreadcrumbTrail $trail, Page $page) {
    $trail->parent('page_detail', $page);
    $trail->push(__('common::multidata.name'), route('cmsadmin.pages.multidata', $page));
});

// SiteSetup >>  page >> Name >> PageDetail
Breadcrumbs::for('page_multidata_details', function (BreadcrumbTrail $trail, Page $page) {
    $trail->parent('page_detail', $page);
    $trail->push(__('cmsadmin::models/pages.page_multidata.singular'), route('cmsadmin.pageDetails.index', $page));
});

// SiteSetup >> Banner
Breadcrumbs::for('banner', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/banners.plural'), route('cmsadmin.banners.index'));
});

// SiteSetup >> Banner >> Trash
Breadcrumbs::for('banner_trash', function (BreadcrumbTrail $trail) {
    $trail->parent('banner');
    $trail->push(__('common::crud.trash'), route('cmsadmin.banners.trashList'));
});

// SiteSetup >> Banner >> Create
Breadcrumbs::for('banner_create', function (BreadcrumbTrail $trail) {
    $trail->parent('banner');
    $trail->push(__('common::crud.create'), route('cmsadmin.banners.create'));
});

// SiteSetup >> Banner >> Name
Breadcrumbs::for('banner_detail', function (BreadcrumbTrail $trail, Banner $banner) {
    $trail->parent('banner');
    $trail->push($banner->title, route('cmsadmin.banners.show', $banner));
});

// SiteSetup >> Banner >> Name >> Edit
Breadcrumbs::for('banner_edit', function (BreadcrumbTrail $trail, Banner $banner) {
    $trail->parent('banner_detail', $banner);
    $trail->push(__('common::crud.edit'), route('cmsadmin.banners.edit', $banner));
});

// Banner Management >> Banner>> Trash >> Name
Breadcrumbs::for('banner_trash_detail', function (BreadcrumbTrail $trail, Banner $banner) {
    $trail->parent('banner_trash');
    $trail->push($banner->title, route('cmsadmin.banners.show', $banner));
});

// Csp Header
Breadcrumbs::for('CspHeader', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cmsadmin::models/csp_headers.singular'), route('cmsadmin.cspHeaders.index'));
});
