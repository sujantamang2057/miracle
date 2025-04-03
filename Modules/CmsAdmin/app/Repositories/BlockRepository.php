<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\Block;

class BlockRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'block_name',
        'filename',
        'file_contents',
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
        return Block::class;
    }
}
