<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\News;

class NewsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'news_title',
        'slug',
        'category_id',
        'description',
        'banner_image',
        'feature_image',
        'published_date',
        'publish_date_from',
        'publish_date_to',
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
        return News::class;
    }
}
