<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\VideoGallery;

class VideoGalleryRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'album_id',
        'caption',
        'video_url',
        'details',
        'feature_image',
        'published_date',
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
        return VideoGallery::class;
    }
}
