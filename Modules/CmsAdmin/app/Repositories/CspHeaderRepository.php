<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\CspHeader;

class CspHeaderRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'directive',
        'keyword',
        'value',
        'schema',
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
        return CspHeader::class;
    }
}
