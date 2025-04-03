<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\PageDetail;

class PageDetailRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'page_title',
        'slug',
        'page_type',
        'page_file_name',
        'meta_keyword',
        'meta_description',
        'description',
        'banner_image',
        'published_date',
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
        return PageDetail::class;
    }
}
