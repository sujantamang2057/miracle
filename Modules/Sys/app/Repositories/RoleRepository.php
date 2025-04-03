<?php

namespace Modules\Sys\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\Sys\app\Models\Role;

class RoleRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'guard_name',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Role::class;
    }
}
