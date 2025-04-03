<div class="card card-primary">
    <div class="card-header bg-danger">
        <h3 class="card-title">
            <a href="{{ route('cmsadmin.posts.index') }}">
                <i class="fa fa-list-ul" aria-hidden="true"></i>
                {{ __('sys::models/dashboard.fields.latest_posts') }}
            </a>
        </h3>
    </div>

    <div class="card-body" style="display: block;">
        <ul class="nav row">
            @if (!empty($postData->count() > 0))
                @foreach ($postData as $key => $post)
                    <li class="nav-item col-md-12 mb-2">
                        <p class="text-muted mb-0">
                            <span class="post-date">{{ $post->published_date }}</span>
                        </p>
                        <h4>
                            @php
                                $html = getPublishIcon($post->publish) . trimText($post->post_title, DEFAULT_TRIM_LEN, 'all');
                            @endphp

                            @if (checkCmsAdminPermission('posts.show'))
                                <a href="{{ route('cmsadmin.posts.show', $post->post_id) }}">
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
