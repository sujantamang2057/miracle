<?php

namespace Modules\Cms\app\Http\Controllers;

use Modules\Cms\app\Components\Helpers\VideoAlbumHelper;
use Modules\Common\app\Http\Controllers\FrontendController;

class VideoAlbumController extends FrontendController
{
    /**
     * Display a listing of the video album.
     */
    public function index()
    {
        $videoAlbumList = VideoAlbumHelper::getCategoryList(null, null, true);

        return view('cms::video_albums.index')
            ->with('videoAlbumList', $videoAlbumList);
    }

    public function gallery($slug)
    {
        $category = VideoAlbumHelper::getCategoryList($slug);

        if (empty($category)) {
            return redirect()->route('cms.videoAlbums.index');
        }

        $galleryList = VideoAlbumHelper::getVideoDetails($category->album_id, null, true);

        return view('cms::video_albums.gallery-list')
            ->with(['category' => $category, 'galleryList' => $galleryList]);
    }
}
