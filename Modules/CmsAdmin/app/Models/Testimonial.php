<?php

namespace Modules\CmsAdmin\app\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\app\Models\Common;

class Testimonial extends Common
{
    use SoftDeletes;

    public $table = 'cms_testimonial';

    public $primaryKey = 'testimonial_id';

    public $fillable = [
        'tm_name',
        'tm_email',
        'tm_profile_image',
        'tm_company',
        'tm_designation',
        'tm_testimonial',
        'sns_fb',
        'sns_linkedin',
        'sns_twitter',
        'sns_instagram',
        'sns_youtube',
        'show_order',
        'publish',
        'reserved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tm_name' => 'string',
        'tm_email' => 'string',
        'tm_profile_image' => 'string',
        'tm_company' => 'string',
        'tm_designation' => 'string',
        'tm_testimonial' => 'string',
        'sns_fb' => 'string',
        'sns_linkedin' => 'string',
        'sns_twitter' => 'string',
        'sns_instagram' => 'string',
        'sns_youtube' => 'string',
    ];
}
