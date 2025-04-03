<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\ArticlesCategory;

class ArticlesCategoryRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'category_name',
        'slug',
        'parent_category_id',
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
        return ArticlesCategory::class;
    }
}
