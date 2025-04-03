<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\BlogDetail;

class BlogDetailRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'detail_id',
        'blog_id',
        'title',
        'image',
        'detail',
        'layout',
        'show_order',
        'publish',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return BlogDetail::class;
    }
}
