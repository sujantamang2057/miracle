<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class Banner extends Common
{
    use SoftDeletes;

    public $table = 'cms_banner';

    public $primaryKey = 'banner_id';

    public $fillable = [
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

    protected $casts = [
        'title' => 'string',
        'url' => 'string',
    ];
}
