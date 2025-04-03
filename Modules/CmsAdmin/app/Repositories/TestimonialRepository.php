<?php

namespace Modules\CmsAdmin\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\CmsAdmin\app\Models\Testimonial;

class TestimonialRepository extends BaseRepository
{
    protected $fieldSearchable = [
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

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Testimonial::class;
    }
}
