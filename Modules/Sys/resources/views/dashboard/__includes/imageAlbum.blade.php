<div class="card card-primary">
    <div class="card-header bg-maroon">
        <h3 class="card-title">
            <a href="{{ route('cmsadmin.albums.index') }}">
                <i class="fa fa-list-ul" aria-hidden="true"></i>
                {{ __('sys::models/dashboard.fields.latest_image_album') }}
            </a>
        </h3>
    </div>

    <div class="card-body" style="display: block;">
        <ul class="nav row">
            @if (!empty($imageAlbumData->count() > 0))
                @foreach ($imageAlbumData as $key => $album)
                    <li class="nav-item col-md-12">
                        <div class="row">
                            <div class="col">
                                <h4>
                                    @php
                                        $html = getPublishIcon($album->publish) . trimText($album->title, DEFAULT_TRIM_LEN, 'all');
                                    @endphp

                                    @if (checkCmsAdminPermission('albums.show'))
                                        <a href="{{ route('cmsadmin.albums.show', $album->album_id) }}">
                                            {!! $html !!}
                                        </a>
                                    @else
                                        {!! $html !!}
                                    @endif
                                </h4>
                            </div>
                            <div class="col-4">
                                @if (!empty($album->coverImage?->image_name))
                                    <figure>
                                        {!! renderFancyboxImage(ALBUM_FILE_DIR_NAME, $album->coverImage?->image_name, 200) !!}
                                    </figure>
                                @endif
                            </div>
                        </div>
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
