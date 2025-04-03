<div class="card card-primary">
    <div class="card-header bg-dark">
        <h3 class="card-title">
            <a href="{{ route('cmsadmin.banners.index') }}">
                <i class="fa fa-list-ul" aria-hidden="true"></i>
                {{ __('sys::models/dashboard.fields.latest_banner') }}
            </a>
        </h3>
    </div>

    <div class="card-body" style="display: block;">
        <ul class="nav row">
            @if (!empty($bannerData->count() > 0))
                @foreach ($bannerData as $key => $banner)
                    <li class="nav-item col-md-12">
                        <div class = "row">
                            <div class ="col">
                                <h4>
                                    @php
                                        $html = getPublishIcon($banner->publish) . trimText($banner->title, DEFAULT_TRIM_LEN, 'all');
                                    @endphp

                                    @if (checkCmsAdminPermission('banners.show'))
                                        <a href="{{ route('cmsadmin.banners.show', $banner->banner_id) }}">
                                            {!! $html !!}</a>
                                    @else
                                        {!! $html !!}
                                    @endif
                                </h4>
                            </div>
                            <div class = "col-4">
                                @if (!empty($banner->pc_image))
                                    <figure>
                                        {!! renderFancyboxImage(BANNER_FILE_DIR_NAME, $banner->pc_image, IMAGE_WIDTH_200) !!}
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
