<div class="card card-primary">
    <div class="card-header bg-navy">
        <h3 class="card-title">
            <a href="{{ route('cmsadmin.resources.index') }}">
                <i class="fa fa-list-ul" aria-hidden="true"></i>
                {{ __('sys::models/dashboard.fields.latest_resource') }}
            </a>
        </h3>
    </div>

    <div class="card-body" style="display: block;">
        <ul class="nav row">
            @if (!empty($resourceData->count() > 0))
                @foreach ($resourceData as $key => $resource)
                    <li class="nav-item col-md-12 mb-2">
                        <h4>
                            @php
                                $html = getPublishIcon($resource->publish) . trimText($resource->display_name, DEFAULT_TRIM_LEN, 'all');
                            @endphp

                            @if (checkCmsAdminPermission('resources.show'))
                                <a href="{{ route('cmsadmin.resources.show', $resource->resource_id) }}">
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
