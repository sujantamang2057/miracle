<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\PostDetail;

class PostDetailRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'post_title',
        'slug',
        'category_id',
        'description',
        'banner_image',
        'feature_image',
        'publish_date_from',
        'publish_date_to',
        'published_date',
        'view_count',
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
        return PostDetail::class;
    }
}
