<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\Blog;

class BlogRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'cat_id',
        'title',
        'slug',
        'thumb_image',
        'image',
        'detail',
        'video_url',
        'display_date',
        'publish_from',
        'publish_to',
        'remarks',
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
        return Blog::class;
    }
}
