<?php

namespace Modules\Cms\app\Components\Helpers;

use Modules\CmsAdmin\app\Models\VideoAlbum;
use Modules\CmsAdmin\app\Models\VideoGallery;

class VideoAlbumHelper
{
    // get VideoAlbum  Data

    public static function getCategoryList($slug = null, $limit = null, $pagination = false)
    {
        $query = VideoAlbum::published()->activeNow();
        if (empty($slug)) {
            $havingVideoAlbums = function ($q) {
                $q->published()->activeNow()->orderBy('show_order', 'desc');
            };
            $query->whereHas('videoGalleries', $havingVideoAlbums)->with(['videoGalleries' => $havingVideoAlbums]);
        }

        $query->orderBy('show_order', 'desc');
        if (!empty($slug)) {
            $data = $query->where('slug', $slug)->first();
        } elseif ($pagination == true) {
            $data = $query->paginate(VIDEO_ALBUM_LIST_PAGINATE_LIMIT);
        } else {
            $data = $query->limit($limit)->get();
        }

        return $data;
    }

    public static function getVideoDetails($albumId = null, $limit = null, $pagination = false)
    {

        $query = VideoGallery::published()->activeNow()
            ->whereHas('album', function ($q) {
                $q->published();
            })
            ->with([
                'album' => function ($q) {
                    $q->published();
                },
            ]);

        if (!empty($albumId)) {
            $query->where('album_id', $albumId);
        }
        $query->orderBy('show_order', 'desc');

        if ($pagination) {
            $data = $query->paginate(VIDEO_GALLERY_LIST_LIMIT);
        } else {
            $data = $query->limit($limit)->get();
        }

        return $data;
    }
}
