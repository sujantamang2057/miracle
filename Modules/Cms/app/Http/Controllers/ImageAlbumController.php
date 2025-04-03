<?php

namespace Modules\Cms\app\Http\Controllers;

use Modules\Cms\app\Components\Helpers\AlbumHelper;
use Modules\Common\app\Http\Controllers\FrontendController;

class ImageAlbumController extends FrontendController
{
    /**
     * Display a listing of the album.
     */
    public function index()
    {
        $imageAlbum = AlbumHelper::getAlbumDetails(null, null, true);

        return view('cms::image_album.index')
            ->with('imageAlbum', $imageAlbum);
    }

    public function galleryList($slug)
    {
        $album = AlbumHelper::getAlbumDetails($slug);
        if (empty($album)) {
            return redirect()->route('cms.imageAlbums.index');
        }

        $galleryImages = AlbumHelper::getGalleryAlbum($album->album_id, null, true);

        return view('cms::image_album.gallery-list')
            ->with('album', $album)
            ->with('galleryImages', $galleryImages);
    }
}
