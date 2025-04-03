<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\Faq;

class FaqRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'faq_cat_id',
        'question',
        'answer',
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
        return Faq::class;
    }
}
