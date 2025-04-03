<?php

namespace Modules\Cms\app\Components\Helpers;

use Modules\CmsAdmin\app\Models\Album;
use Modules\CmsAdmin\app\Models\Gallery;

class AlbumHelper
{
    public static function getAlbumDetails($slug = null, $limit = null, $pagination = null)
    {
        $isRelationPublished = function ($q) {
            $q->published();
        };

        $query = Album::published()->activeNow()
            ->whereHas('galleries', $isRelationPublished)
            ->with([
                'galleries' => $isRelationPublished,
                'coverImage' => $isRelationPublished,
            ]);
        if (empty($slug)) {
            $havingAlbums = function ($q) {
                $q->published()->orderBy('show_order', 'desc');
            };
            $query->whereHas('galleries', $havingAlbums)->with(['galleries' => $havingAlbums]);
        }

        $query->orderBy('show_order', 'desc');
        if (!empty($slug)) {
            $data = $query->where('slug', $slug)->first();
        } elseif ($pagination == true) {
            $data = $query->paginate(TOP_PAGE_ALBUM_LIMIT);
        } else {
            $data = $query->limit($limit)->get();
        }

        return $data;
    }

    public static function getGalleryAlbum($albumId = null, $limit = null, $pagination = false)
    {
        $query = Gallery::published()
            ->whereHas('album', function ($q) {
                $q->published()->activeNow();
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
            $data = $query->paginate(GALLERY_LIST_LIMIT);
        } else {
            $data = $query->limit($limit)->get();
        }

        return $data;
    }
}
