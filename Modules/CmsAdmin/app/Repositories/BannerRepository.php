<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\Banner;

class BannerRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'title',
        'description',
        'pc_image',
        'sp_image',
        'url',
        'url_target',
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
        return Banner::class;
    }
}
