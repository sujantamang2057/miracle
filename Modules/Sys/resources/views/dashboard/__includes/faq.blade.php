<div class="card card-primary">
    <div class="card-header bg-purple">
        <h3 class="card-title">
            <a href="{{ route('cmsadmin.faqs.index') }}">
                <i class="fa fa-list-ul" aria-hidden="true"></i>
                {{ __('sys::models/dashboard.fields.latest_faq') }}
            </a>
        </h3>
    </div>

    <div class="card-body" style="display: block;">
        <ul class="nav row">
            @if (!empty($faqData->count() > 0))
                @foreach ($faqData as $key => $faq)
                    <li class="nav-item col-md-12 mb-2">
                        <h4>
                            @php
                                $html = getPublishIcon($faq->publish) . trimText($faq->question, DEFAULT_TRIM_LEN, 'all');
                            @endphp

                            @if (checkCmsAdminPermission('faqs.show'))
                                <a href="{{ route('cmsadmin.faqs.show', $faq->faq_id) }}">{!! $html !!} </a>
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
