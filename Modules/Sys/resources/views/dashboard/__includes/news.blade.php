<div class="card card-primary">
    <div class="card-header bg-yellow">
        <h3 class="card-title">
            <a href="{{ route('cmsadmin.news.index') }}">
                <i class="fa fa-list-ul" aria-hidden="true"></i>
                {{ __('sys::models/dashboard.fields.latest_news') }}
            </a>
        </h3>
    </div>

    <div class="card-body" style="display: block;">
        <ul class="nav row">
            @if (!empty($newsData->count() > 0))
                @foreach ($newsData as $key => $news)
                    <li class="nav-item col-md-12 mb-2">
                        <p class="text-muted mb-0">
                            <span class="news-date">{{ $news->published_date }}</span>
                        </p>
                        <h4>
                            @php
                                $html = getPublishIcon($news->publish) . trimText($news->news_title, DEFAULT_TRIM_LEN, 'all');
                            @endphp

                            @if (checkCmsAdminPermission('news.show'))
                                <a href="{{ route('cmsadmin.news.show', $news->news_id) }}">
                                    {!! $html !!}
                                </a>
                            @else
                                {!! $html !!}
                            @endif
                        </h4>
                    </li>
                @endforeach
            @else
                <li class="nav-item col-md-12 mb-2">
                    <h4 class="text-black">{{ __('sys::models/dashboard.data_not_found') }}</h4>
                </li>
            @endif
        </ul>
    </div>

</div>
