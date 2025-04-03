<!-- Title Field -->
<div class="col-sm-12">
    {!! Form::label('title', __('cmsadmin::models/banners.fields.title')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $banner->title }}</p>
</div>

<!-- Description Field -->
<div class="col-sm-12">
    {!! Form::label('description', __('cmsadmin::models/banners.fields.description')) !!}
    <span class="semicolon mr-3">:</span>
    <pre>{!! $banner->description !!}</pre>
</div>

<!-- Pc Image Field -->
<div class="col-sm-12">
    {!! Form::label('pc_image', __('cmsadmin::models/banners.fields.pc_image')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! renderFancyboxImage(BANNER_FILE_DIR_NAME, $banner->pc_image, IMAGE_WIDTH_200) !!}</p>
</div>

<!-- Sp Image Field -->
<div class="col-sm-12">
    {!! Form::label('sp_image', __('cmsadmin::models/banners.fields.sp_image')) !!}
    <span class="semicolon mr-3">:</span>
    <div class="d-flex align-items-end">
        <p>
            @if (!empty($banner->sp_image))
                <div class="mr-1">
                    {!! renderFancyboxImage(BANNER_FILE_DIR_NAME, $banner->sp_image, IMAGE_WIDTH_200) !!}
                </div>
                @if ($mode !== 'trash-restore')
                    {!! renderImageDeleteIcon(route('cmsadmin.banners.removeImage', [$banner->banner_id]), 'sp_image') !!}
                @endif
            @endif
        </p>
    </div>
</div>

<!-- Url Field -->
<div class="col-sm-12">
    {!! Form::label('url', __('cmsadmin::models/banners.fields.url')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $banner->url }}</p>
</div>

<!-- Url Target Field -->
<div class="col-sm-12">
    {!! Form::label('url_target', __('cmsadmin::models/banners.fields.url_target')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! getUrlTargetText($banner->url_target) !!}</p>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getPublishText($banner->publish) }}</p>
    @else
        <p>{{ renderBsSwitchGrid('publish', $banner->banner_id, $banner->publish, route('cmsadmin.banners.togglePublish')) }}
        </p>
    @endif
</div>

<!-- Reserved Field -->
<div class="col-sm-12">
    {!! Form::label('reserved', __('common::crud.fields.reserved')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getReservedText($banner->reserved) }}</p>
    @else
        <p>{{ renderBsSwitchGrid('reserved', $banner->banner_id, $banner->reserved, route('cmsadmin.banners.toggleReserved')) }}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $banner->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $banner->created_by ? getUserDataById($banner->created_by) : '' }}</p>
</div>

@if ($banner->updated_at)
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $banner->updated_at }}</p>
    </div>
@endif

@if ($banner->updated_by)
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataById($banner->updated_by) }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($banner->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $banner->deleted_at }}</p>
        </div>
    @endif

    @if ($banner->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($banner->deleted_by) }}</p>
        </div>
    @endif
@endif
