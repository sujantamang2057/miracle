<div class="card card-primary">
    <div class="card-header bg-gradient-info">
        <h3 class="card-title">
            <a href="{{ route('cmsadmin.videoAlbums.index') }}">
                <i class="fa fa-list-ul" aria-hidden="true"></i>
                {{ __('sys::models/dashboard.fields.latest_video_album') }}
            </a>
        </h3>
    </div>

    <div class="card-body" style="display: block;">
        <ul class="nav row">
            @if (!empty($videoAlbumData->count() > 0))
                @foreach ($videoAlbumData as $key => $video)
                    <li class="nav-item col-md-12 mb-2">
                        <h4>

                            <h4>
                                @php
                                    $html = getPublishIcon($video->publish) . trimText($video->album_name, DEFAULT_TRIM_LEN, 'all');
                                @endphp

                                @if (checkCmsAdminPermission('videoAlbums.show'))
                                    <a href="{{ route('cmsadmin.videoAlbums.show', $video->album_id) }}">
                                        {!! $html !!}
                                    </a>
                                @else
                                    {!! $html !!}
                                @endif
                            </h4>
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
