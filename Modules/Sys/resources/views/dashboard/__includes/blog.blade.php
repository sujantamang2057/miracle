<div class="card card-primary">
    <div class="card-header bg-blue">
        <h3 class="card-title">
            <a href="{{ route('cmsadmin.blogs.index') }}">
                <i class="fa fa-list-ul" aria-hidden="true"></i>
                {{ __('sys::models/dashboard.fields.latest_blog') }}
            </a>
        </h3>
    </div>

    <div class="card-body" style="display: block;">
        <ul class="nav row">
            @if (!empty($blogData->count() > 0))
                @foreach ($blogData as $key => $blog)
                    <li class="nav-item col-md-12 mb-2">
                        <p class="text-muted mb-0">
                            <span class="post-date">{{ $blog->display_date }}</span>
                        </p>
                        <h4>
                            @php
                                $html = getPublishIcon($blog->publish) . trimText($blog->title, DEFAULT_TRIM_LEN, 'all');
                            @endphp

                            @if (checkCmsAdminPermission('blogs.show'))
                                <a href="{{ route('cmsadmin.blogs.show', $blog->blog_id) }}">
                                    {!! $html !!}</a>
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
