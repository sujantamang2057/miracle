<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\Album;

class AlbumRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'date',
        'title',
        'slug',
        'detail',
        'cover_image_id',
        'image_count',
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
        return Album::class;
    }
}
