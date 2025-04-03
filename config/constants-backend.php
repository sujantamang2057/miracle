<?php

/**
 * constants-backend.php
 */

// AUTH
define('AUTH_LOGIN_REDIRECT_URL_SUCCESS', '/backend/dashboard');
define('AUTH_REDIRECT_DEFAULT_URL', '/home');

// Backend css, js
define('BACKEND_URL_CSS', 'theme/infyom/css/backend.css');
define('BACKEND_URL_JS', 'theme/infyom/js/backend.js');

// Limits - Min/Max values
define('MIN_VALUE_PHONE', 0);
define('MAX_VALUE_PHONE', 999999999999);

// trim length
define('TRIM_END_STR', '...');
define('DASHBOARD_NEWS_TRIM_LEN', 200);
define('DASHBOARD_BLOG_TRIM_LEN', 200);

//
define('REPLACE_KEYWORDS_TITLE', json_encode([',', '"', "'"]));

// datetimepicker
define('DATETIMEPICKER_DATE_FORMAT', 'YYYY-MM-DD');
define('DATETIMEPICKER_DATETIME_FORMAT', 'YYYY-MM-DD HH:mm:ss');

// pagination
define('BACKEND_PAGESIZES', json_encode([10, 25, 50, 100, 200]));
define('BACKEND_PAGESIZES_LABEL', json_encode(['10 rows', '25 rows', '50 rows', '100 rows', '200 rows']));

// enable toggle locale list
define('ENABLE_LOCALE_TOGGLE', ['index', 'show', 'trashList']);

// active fields in tables
define('ACTIVE_FIELD_TABLES', ['cms_menu', 'users']);

//
define('DEFAULT_PAGE_SIZE_ALBUM', 25);
define('DEFAULT_PAGE_SIZE_BANNER', 25);
define('DEFAULT_PAGE_SIZE_BLOCK', 25);
define('DEFAULT_PAGE_SIZE_BLOG', 25);
define('DEFAULT_PAGE_SIZE_BLOG_CATEGORY', 10);
define('DEFAULT_PAGE_SIZE_BLOG_DETAIL', 25);
define('DEFAULT_PAGE_SIZE_BLOG_IMAGE', 25);
define('DEFAULT_PAGE_SIZE_CONTACT', 25);
define('DEFAULT_PAGE_SIZE_GALLERY', 25);
define('DEFAULT_PAGE_SIZE_MENU', 25);
define('DEFAULT_PAGE_SIZE_NEWS', 25);
define('DEFAULT_PAGE_SIZE_NEWS_CATEGORY', 25);
define('DEFAULT_PAGE_SIZE_NEWS_DETAIL', 25);
define('DEFAULT_PAGE_SIZE_PAGE', 25);
define('DEFAULT_PAGE_SIZE_PAGE_DETAIL', 25);
define('DEFAULT_PAGE_SIZE_POST_CATEGORY', 25);
define('DEFAULT_PAGE_SIZE_POST', 25);
define('DEFAULT_PAGE_SIZE_POST_DETAIL', 25);
define('DEFAULT_PAGE_SIZE_FAQ_CATEGORY', 25);
define('DEFAULT_PAGE_SIZE_FAQ', 25);
define('DEFAULT_PAGE_SIZE_RESOURCE', 25);
define('DEFAULT_PAGE_SIZE_SEO', 25);
define('DEFAULT_PAGE_SIZE_TESTIMONIAL', 25);
define('DEFAULT_PAGE_SIZE_VIDEO_ALBUM', 25);
define('DEFAULT_PAGE_SIZE_VIDEO_GALLERY', 25);
define('DEFAULT_PAGE_SIZE_SNS', 25);
define('DEFAULT_PAGE_SIZE_EMAIL_TEMPLATE', 25);

define('DEFAULT_PAGE_SIZE_USER', 25);
define('DEFAULT_PAGE_SIZE_PERMISSION', 50);
define('DEFAULT_PAGE_SIZE_ROLE', 25);

// limits
define('BACKEND_POST_LIMIT', 4);
define('BACKEND_NEWS_LIMIT', 4);
define('BACKEND_BLOG_LIMIT', 4);
define('BACKEND_BANNER_LIMIT', 4);
define('BACKEND_FAQ_LIMIT', 4);
define('BACKEND_TESTIMONIAL_LIMIT', 4);
define('BACKEND_RESOURCE_LIMIT', 4);
define('BACKEND_IMAGE_ALBUM_LIMIT', 4);
define('BACKEND_VIDEO_ALBUM_LIMIT', 4);
define('BACKEND_CONTACT_LIMIT', 4);

// auto purge trash data
define('PURGE_TRASH_DATA_TABLE_ARRAY', ['cms_block']);
define('PURGE_TRASH_DATA_MONTH', 6);
