<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class Seo extends Common
{
    use SoftDeletes;

    public $table = 'seo_metadata';

    public $primaryKey = 'id';

    public $fillable = [
        'module_name',
        'code',
        'title',
        'title_locale',
        'keyword',
        'keyword_locale',
        'description',
        'description_locale',
        'publish',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'module_name' => 'string',
        'code' => 'string',
        'title' => 'string',
        'title_locale' => 'string',
        'keyword' => 'string',
        'keyword_locale' => 'string',
        'description' => 'string',
        'description_locale' => 'string',
        'publish' => 'integer',
    ];
}
