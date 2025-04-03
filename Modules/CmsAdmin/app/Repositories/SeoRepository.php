<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\Seo;

class SeoRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'module_name',
        'code',
        'title',
        'title_locale',
        'keyword',
        'keyword_locale',
        'description',
        'description_locale',
        'created_by',
        'updated_by',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Seo::class;
    }
}
