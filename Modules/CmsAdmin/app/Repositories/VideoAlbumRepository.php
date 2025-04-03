<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\VideoAlbum;

class VideoAlbumRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'album_name',
        'slug',
        'album_date',
        'description',
        'show_order',
        'publish',
        'reserved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return VideoAlbum::class;
    }
}
