<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\BlogCategory;

class BlogCategoryRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'cat_title',
        'cat_slug',
        'cat_image',
        'remarks',
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
        return BlogCategory::class;
    }
}
