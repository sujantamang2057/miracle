<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\Menu;

class MenuRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'parent_id',
        'title',
        'slug',
        'url',
        'url_type',
        'url_target',
        'css_class',
        'tooltip',
        'show_order',
        'active',
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
        return Menu::class;
    }
}
