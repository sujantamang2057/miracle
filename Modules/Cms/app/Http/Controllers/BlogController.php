<?php

namespace Modules\Cms\app\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Cms\app\Components\Helpers\BlogHelper;
use Modules\Common\app\Http\Controllers\FrontendController;

class BlogController extends FrontendController
{
    /**
     * Display a listing of the BlogCategory .
     */
    public function index()
    {
        $blogCatList = BlogHelper::getCategoryList(null, BLOG_CATEGORY_LIST_LIMIT, true);

        return view('cms::blogs.index')
            ->with('blogCatList', $blogCatList);
    }

    public function search(Request $request)
    {
        $freetext = $request->input('search');
        $blogList = BlogHelper::getBlogData(null, null, null, true, null, $freetext);
        $totalData = $blogList->total();

        return view('cms::blogs.search')
            ->with('totalData', $totalData)
            ->with('freetext', $freetext)
            ->with('blogList', $blogList);
    }

    public function blogList($catSlug)
    {
        $category = BlogHelper::getCategoryList($catSlug);

        if (empty($category)) {
            return redirect()->route('cms.blogs.index');
        }

        $blogList = BlogHelper::getBlogData(null, $category->cat_id, null, true);

        return view('cms::blogs.blog_list')->with(['category' => $category, 'blogList' => $blogList]);
    }

    public function detail($slug)
    {
        $blog = BlogHelper::getBlogData($slug);

        if (!empty($blog)) {
            $blogId = $blog->blog_id ?? '';
            $catID = $blog->cat_id ?? '';
            $relatedBlogs = !empty($catID) ? BlogHelper::getBlogData(null, $catID, RELATED_BLOG_LIMIT, false, $blogId) : null;

            return view('cms::blogs.detail')
                ->with('blog', $blog)
                ->with('relatedBlogs', $relatedBlogs);
        } else {
            return redirect()->route('cms.blogs.index');
        }
    }
}
