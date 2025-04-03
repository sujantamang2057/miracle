<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\NewsDetail;

class NewsDetailRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'news_id',
        'title',
        'image',
        'detail',
        'layout',
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
        return NewsDetail::class;
    }
}
