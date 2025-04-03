<?php

namespace Modules\Sys\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\Sys\app\Models\Sns;

class SnsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'title',
        'icon',
        'class',
        'url',
        'show_order',
        'publish',
        'reserved',
        'created_by',
        'updated_by',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Sns::class;
    }
}
