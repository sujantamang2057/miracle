<div class="card card-primary">
    <div class="card-header bg-orange">
        <h3 class="card-title">
            <a href="{{ route('cmsadmin.testimonials.index') }}">
                <i class="fa fa-list-ul" aria-hidden="true"></i>
                {{ __('sys::models/dashboard.fields.latest_testimonial') }}
            </a>
        </h3>
    </div>

    <div class="card-body" style="display: block;">
        <ul class="nav row">
            @if (!empty($testimonialData->count() > 0))
                @foreach ($testimonialData as $key => $testimonial)
                    <li class="nav-item col-md-12 mb-2">
                        <h4>
                            @php
                                $html = getPublishIcon($testimonial->publish) . trimText($testimonial->tm_name, DEFAULT_TRIM_LEN, 'all');
                            @endphp

                            @if (checkCmsAdminPermission('testimonials.show'))
                                <a href="{{ route('cmsadmin.testimonials.show', $testimonial->testimonial_id) }}">
                                    {!! $html !!}
                                </a>
                            @else
                                {!! $html !!}
                            @endif
                        </h4>
                    </li>
                @endforeach
            @else
                <li class="nav-item">
                    <h4 class="text-black">{{ __('sys::models/dashboard.data_not_found') }}</h4>
                </li>
            @endif
        </ul>
    </div>

</div>
