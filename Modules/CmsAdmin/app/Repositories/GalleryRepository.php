<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\Gallery;

class GalleryRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'album_id',
        'caption',
        'image_name',
        'show_order',
        'publish',
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
        return Gallery::class;
    }
}
