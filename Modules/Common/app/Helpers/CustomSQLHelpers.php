<?php

/**
 * Custom SQL Helper Functions
 * /app/Helpers/CustomSQLHelpers.php
 */

use App\Models\User;
use Modules\CmsAdmin\app\Models\Blog;
use Modules\CmsAdmin\app\Models\BlogCategory;
use Modules\CmsAdmin\app\Models\Faq;
use Modules\CmsAdmin\app\Models\FaqCategory;
use Modules\CmsAdmin\app\Models\Menu;
use Modules\CmsAdmin\app\Models\News;
use Modules\CmsAdmin\app\Models\NewsCategory;
use Modules\CmsAdmin\app\Models\Post;
use Modules\CmsAdmin\app\Models\PostCategory;
use Modules\Sys\app\Models\EmailTemplate;
use Modules\Sys\app\Models\Role;

function getUserDataById($userId = null, $field = 'name')
{
    return User::getSingleFieldData($userId, $field);
}

function getEmailTemplate($mailCode)
{
    return !empty($mailCode) ? EmailTemplate::where('mail_code', $mailCode)->first() : null;
}

function getRoleListData()
{
    return getDropdownData(Role::class, 'id', 'name');
}

function getNewsCategoryListData()
{
    return getDropdownData(NewsCategory::class, 'category_id', 'category_name', News::class);
}

function getPostCategoryListData()
{
    return getDropdownData(PostCategory::class, 'category_id', 'category_name', Post::class);
}

function getFaqCategoryListData()
{
    return getDropdownData(FaqCategory::class, 'faq_cat_id', 'faq_cat_name', Faq::class);
}

function getBlogCategoryListData()
{
    return getDropdownData(BlogCategory::class, 'cat_id', 'cat_title', Blog::class);
}

function getMenuParentListData()
{
    return getDropdownData(Menu::class, 'menu_id', 'title', null);
}
