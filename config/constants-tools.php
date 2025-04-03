<?php

// space report
define('SPACE_REPORT_PATH_ARRAY', [
    'db_backup' => [
        'name_key' => 'db_backup',
        'path' => storage_path('app/' . env('APP_NAME')),
        'bg_color' => 'cyan',
        'color' => '#00c0ef',
    ],
    'blocks_backup' => [
        'name_key' => 'blocks_backup',
        'path' => base_path('resources/views/components/blocks/'),
        'bg_color' => 'navy',
        'color' => '#001f3f',
    ],
    'pages_backup' => [
        'name_key' => 'pages_backup',
        'path' => base_path('resources/views/components/pages_dynamic/'),
        'bg_color' => 'purple',
        'color' => '#605ca8',
    ],
    'email_template_backup' => [
        'name_key' => 'email_template_backup',
        'path' => base_path('resources/views/components/emailTemplates/'),
        'bg_color' => 'teal',
        'color' => '#47c29d',
    ],
    'logs' => [
        'name_key' => 'logs',
        'path' => storage_path('logs/'),
        'bg_color' => 'green',
        'color' => '#00a65a',
    ],
    'storage_temp_files' => [
        'name_key' => 'storage_temp_files',
        'path' => storage_path('tmp/'),
        'bg_color' => 'yellow',
        'color' => '#f39c12',
    ],
    'storage_images' => [
        'name_key' => 'storage_images',
        'path' => storage_path('app/public/'),
        'bg_color' => 'orange',
        'color' => '#ff851b',
    ],
]);

define('BACKUP_FOLDER_KEY', ['blocks_backup', 'pages_backup', 'email_template_backup']);

// slug regenerator
define('SLUG_REGENERATOR_DATA_FETCH_LIMIT', 5);

define('SLUG_MODULES', [
    'ALBUM' => 'ALBUM',
    'BLOG' => 'BLOG',
    'BLOG_CATEGORY' => 'BLOG CATEGORY',
    'FAQ_CATEGORY' => 'FAQ CATEGORY',
    'MENU' => 'MENU',
    'NEWS' => 'NEWS',
    'NEWS_CATEGORY' => 'NEWS CATEGORY',
    'PAGE' => 'PAGE',
    'POST' => 'POST',
    'POST_CATEGORY' => 'POST CATEGORY',
    'VIDEO_ALBUM' => 'VIDEO ALBUM',
]);

define('SLUG_MODELS', [
    'ALBUM' => "\Modules\CmsAdmin\app\Models\Album",
    'BLOG' => "\Modules\CmsAdmin\app\Models\Blog",
    'BLOG_CATEGORY' => "\Modules\CmsAdmin\app\Models\BlogCategory",
    'FAQ_CATEGORY' => "\Modules\CmsAdmin\app\Models\FaqCategory",
    'MENU' => "\Modules\CmsAdmin\app\Models\Menu",
    'NEWS' => "\Modules\CmsAdmin\app\Models\News",
    'NEWS_CATEGORY' => "\Modules\CmsAdmin\app\Models\NewsCategory",
    'PAGE' => "\Modules\CmsAdmin\app\Models\Page",
    'POST' => "\Modules\CmsAdmin\app\Models\Post",
    'POST_CATEGORY' => "\Modules\CmsAdmin\app\Models\PostCategory",
    'VIDEO_ALBUM' => "\Modules\CmsAdmin\app\Models\VideoAlbum",
]);

define('SLUG_ATTR_NAMES', [
    'ALBUM' => [
        'titleField' => 'title',
        'slugField' => 'slug',
    ],
    'BLOG' => [
        'titleField' => 'title',
        'slugField' => 'slug',
    ],
    'BLOG_CATEGORY' => [
        'titleField' => 'cat_title',
        'slugField' => 'cat_slug',
    ],
    'FAQ_CATEGORY' => [
        'titleField' => 'faq_cat_name',
        'slugField' => 'slug',
    ],
    'MENU' => [
        'titleField' => 'title',
        'slugField' => 'slug',
    ],
    'NEWS' => [
        'titleField' => 'news_title',
        'slugField' => 'slug',
    ],
    'NEWS_CATEGORY' => [
        'titleField' => 'category_name',
        'slugField' => 'slug',
    ],
    'PAGE' => [
        'titleField' => 'page_title',
        'slugField' => 'slug',
    ],
    'POST' => [
        'titleField' => 'post_title',
        'slugField' => 'slug',
    ],
    'POST_CATEGORY' => [
        'titleField' => 'category_name',
        'slugField' => 'slug',
    ],
    'VIDEO_ALBUM' => [
        'titleField' => 'album_name',
        'slugField' => 'slug',
    ],
]);

// image regenerator

define('IMAGE_REGENERATOR_DATA_FETCH_LIMIT', 5);

define('ARRAY_MODULES', [
    'ALBUM' => 'ALBUM',
    'BANNER' => 'BANNER',
    'BLOGS' => 'BLOG',
    'BLOG_CATEGORY' => 'BLOG CATEGORY',
    'BLOG_DETAIL' => 'BLOG DETAIL',
    'FAQ_CATEGORY' => 'FAQ CATEGORY',
    'NEWSS' => 'NEWS',
    'NEWS_DETAIL' => 'NEWS DETAIL',
    'PAGES' => 'PAGE',
    'PAGE_DETAIL' => 'PAGE DETAIL',
    'POSTS' => 'POST',
    'POST_CATEGORY' => 'POST CATEGORY',
    'POST_DETAIL' => 'POST DETAIL',
    'SITE_SETTING' => 'SITE SETTING',
    'SNSS' => 'SNS',
    'TESTIMONIAL' => 'TESTIMONIAL',
    'USER' => 'USER',
    'VIDEO_ALBUM' => 'VIDEO ALBUM',
]);

define('ARRAY_MODELS', [
    'ALBUM' => "\Modules\CmsAdmin\app\Models\Gallery",
    'BANNER' => "\Modules\CmsAdmin\app\Models\Banner",
    'BLOGS' => "\Modules\CmsAdmin\app\Models\Blog",
    'BLOG_CATEGORY' => "\Modules\CmsAdmin\app\Models\BlogCategory",
    'BLOG_DETAIL' => "\Modules\CmsAdmin\app\Models\BlogDetail",
    'FAQ_CATEGORY' => "\Modules\CmsAdmin\app\Models\FaqCategory",
    'NEWSS' => "\Modules\CmsAdmin\app\Models\News",
    'NEWS_DETAIL' => "\Modules\CmsAdmin\app\Models\NewsDetail",
    'PAGES' => "\Modules\CmsAdmin\app\Models\Page",
    'PAGE_DETAIL' => "\Modules\CmsAdmin\app\Models\PageDetail",
    'POSTS' => "\Modules\CmsAdmin\app\Models\Post",
    'POST_CATEGORY' => "\Modules\CmsAdmin\app\Models\PostCategory",
    'POST_DETAIL' => "\Modules\CmsAdmin\app\Models\PostDetail",
    'SITE_SETTING' => "\Modules\Sys\app\Models\SiteSetting",
    'SNSS' => "\Modules\Sys\app\Models\Sns",
    'TESTIMONIAL' => "\Modules\CmsAdmin\app\Models\Testimonial",
    'USER' => "\Modules\Sys\app\Models\User",
    'VIDEO_ALBUM' => "\Modules\CmsAdmin\app\Models\VideoGallery",
]);

define('ARRAY_IMAGE_NAMES', [
    'ALBUM_KEYWORD_IMAGE' => [
        'label' => 'ALBUM IMAGE',
        'field_name' => 'image_name',
    ],
    'BANNER_KEYWORD_PC' => [
        'label' => 'BANNER PC',
        'field_name' => 'pc_image',
    ],
    'BANNER_KEYWORD_SP' => [
        'label' => 'BANNER SP',
        'field_name' => 'sp_image',
    ],
    'BLOGS_KEYWORD_IMAGE' => [
        'label' => 'BLOG IMAGE',
        'field_name' => 'image',
    ],
    'BLOGS_KEYWORD_THUMB' => [
        'label' => 'BLOG THUMB IMAGE',
        'field_name' => 'thumb_image',
    ],
    'BLOG_CATEGORY_KEYWORD_IMAGE' => [
        'label' => 'BLOG CATEGORY IMAGE',
        'field_name' => 'category_image',
    ],
    'BLOG_DETAIL_KEYWORD_IMAGE' => [
        'label' => 'BLOG DETAIL IMAGE',
        'field_name' => 'image',
    ],
    'FAQ_CATEGORY' => [
        'label' => 'FAQ CATEGORY',
        'field_name' => 'faq_cat_image',
    ],
    'NEWSS_KEYWORD_BANNER' => [
        'label' => 'NEWS BANNER',
        'field_name' => 'banner_image',
    ],
    'NEWSS_KEYWORD_FEATURE' => [
        'label' => 'NEWS FEATURE',
        'field_name' => 'feature_image',
    ],
    'NEWS_DETAIL_IMAGE' => [
        'label' => 'NEWS DETAIL IMAGE',
        'field_name' => 'image',
    ],
    'PAGES_KEYWORD_IMAGE' => [
        'label' => 'PAGE IMAGE',
        'field_name' => 'banner_image',
    ],
    'PAGE_DETAIL_KEYWORD_IMAGE' => [
        'label' => 'PAGE DETAIL IMAGE',
        'field_name' => 'image',
    ],
    'POSTS_KEYWORD_BANNER' => [
        'label' => 'POST BANNER',
        'field_name' => 'banner_image',
    ],
    'POSTS_KEYWORD_FEATURE' => [
        'label' => 'POST FEATURE',
        'field_name' => 'feature_image',
    ],
    'POST_CATEGORY_KEYWORD_IMAGE' => [
        'label' => 'POST CATEGORY IMAGE',
        'field_name' => 'category_image',
    ],
    'POST_DETAIL_KEYWORD_IMAGE' => [
        'label' => 'POST DETAIL IMAGE',
        'field_name' => 'image_name',
    ],
    'SITE_SETTING_KEYWORD_LOGO' => [
        'label' => 'SITE LOGO',
        'field_name' => 'site_logo',
    ],
    'SNSS_KEYWORD_ICON' => [
        'label' => 'SNS ICON',
        'field_name' => 'icon',
    ],
    'TESTIMONIAL_KEYWORD_IMAGE' => [
        'label' => 'PROFILE IMAGE',
        'field_name' => 'tm_profile_image',
    ],
    'USER_KEYWORD_IMAGE' => [
        'label' => 'PROFILE IMAGE',
        'field_name' => 'profile_image',
    ],
    'VIDEO_ALBUM_IMAGE' => [
        'label' => 'VIDEO GALLERY',
        'field_name' => 'feature_image',
    ],
]);

// Trash Data Purger
define('PURGE_MONTHS_ARR', [
    '6' => '6 Months',
    '9' => '9 Months',
    '12' => '1 Year',
]);

define('PURGE_MODELS_ARR', [
    'BLOCK' => 'BLOCK',
    'BLOG' => 'BLOG',
    'GALLERY' => 'GALLERY',
    'NEWS' => 'NEWS',
    'PAGE' => 'PAGE',
    'TESTIMONIAL' => 'TESTIMONIAL',
    'VIDEO_GALLERY' => 'VIDEO GALLERY',
]);

define('PURGE_MODELS_DATA_ARR', [
    'BLOCK' => [
        'name' => 'Block',
        'namespace' => '\Modules\CmsAdmin\app\Models\Block',
        'title' => 'block_name',
    ],
    'BLOG' => [
        'name' => 'Blog',
        'namespace' => 'Modules\CmsAdmin\app\Models\Blog',
        'title' => 'title',
    ],
    'GALLERY' => [
        'name' => 'Gallery',
        'namespace' => 'Modules\CmsAdmin\app\Models\Gallery',
        'title' => 'caption',
    ],
    'NEWS' => [
        'name' => 'News',
        'namespace' => 'Modules\CmsAdmin\app\Models\News',
        'title' => 'news_title',
    ],
    'PAGE' => [
        'name' => 'Page',
        'namespace' => 'Modules\CmsAdmin\app\Models\Page',
        'title' => 'page_title',
    ],
    'TESTIMONIAL' => [
        'name' => 'Testimonial',
        'namespace' => 'Modules\CmsAdmin\app\Models\Testimonial',
        'title' => 'tm_name',
    ],
    'VIDEO_GALLERY' => [
        'name' => 'VideoGallery',
        'namespace' => 'Modules\CmsAdmin\app\Models\VideoGallery',
        'title' => 'caption',
    ],

]);

// Backup Cleaner
define('BACKUP_CLEAR_DAYS', 30);

// Image Cleaner
define('TMP_IMAGE_CLEAN_DAYS_OLD', [
    '365' => '365',
    '120' => '120',
    '90' => '90',
    '60' => '60',
    '30' => '30',
    '10' => '10',
    '5' => '5',
]);
