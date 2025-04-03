<?php

namespace Modules\Cms\app\Http\Controllers;

use Modules\Cms\app\Components\Helpers\TestimonialHelper;
use Modules\Common\app\Http\Controllers\FrontendController;

class TestimonialController extends FrontendController
{
    /**
     * Display a listing of the testimonial.
     */
    public function index()
    {
        $testimonials = TestimonialHelper::getTestimonial(null, true);

        return view('cms::testimonials.index')
            ->with('testimonials', $testimonials);
    }
}
