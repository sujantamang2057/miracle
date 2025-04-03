<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\FaqCategory;

class FaqCategoryRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'faq_cat_name',
        'slug',
        'faq_cat_image',
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
        return FaqCategory::class;
    }
}
