<?php

namespace Modules\Cms\app\Components\Helpers;

use Modules\CmsAdmin\app\Models\Testimonial;

class TestimonialHelper
{
    // get Testimonial Module Data
    public static function getTestimonial($limit = null, $pagination = null)
    {
        $query = Testimonial::published()
            ->orderBy('show_order', 'desc')
            ->limit($limit);

        if ($pagination == true) {
            $data = $query->paginate(TESTIMONIAL_LIST_LIMIT);
        } else {
            $data = $query->limit($limit)->get();
        }

        return $data;
    }
}
