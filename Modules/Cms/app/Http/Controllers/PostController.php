<?php

namespace Modules\Cms\app\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Cms\app\Components\Helpers\PostHelper;
use Modules\Common\app\Http\Controllers\FrontendController;

class PostController extends FrontendController
{
    /**
     * Display a listing of the post.
     */
    public function index()
    {
        $postCategoryList = PostHelper::getCategoryList(null, POST_CATEGORY_LIST_LIMIT, true);

        return view('cms::posts.index')
            ->with('postCategoryList', $postCategoryList);
    }

    // render search page
    public function search(Request $request)
    {
        $freetext = $request->input('search');
        $postList = PostHelper::getPostDetails(null, null, null, true, null, $freetext);
        $totalData = $postList->total();

        return view('cms::posts.search')
            ->with('totalData', $totalData)
            ->with('freetext', $freetext)
            ->with('postList', $postList);
    }

    public function postList($slug)
    {
        $category = PostHelper::getCategoryList($slug);

        if (empty($category)) {
            return redirect(route('cms.posts.index'));
        }

        $postList = PostHelper::getPostDetails(null, $category->category_id, null, true);

        return view('cms::posts.post-list')
            ->with('category', $category)
            ->with('postList', $postList);
    }

    public function detail($slug)
    {
        $post = PostHelper::getPostDetails($slug);

        if (!empty($post)) {
            $postId = $post->post_id ?: '';
            $catId = $post->category_id ?: '';
            $relatedPosts = !empty($catId) ? PostHelper::getPostDetails(null, $catId, RELATED_POST_LIMIT, false, $postId) : null;

            return view('cms::posts.detail')
                ->with('post', $post)
                ->with('relatedPosts', $relatedPosts);
        }

        return redirect()->route('cms.posts.index');
    }
}
