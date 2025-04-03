<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\Resource;

class ResourceRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'file_name',
        'display_name',
        'file_size',
        'file_type',
        'download_count',
        'view_count',
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
        return Resource::class;
    }
}
