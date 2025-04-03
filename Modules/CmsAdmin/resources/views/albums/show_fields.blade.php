<!-- Date Field -->
<div class="col-sm-12">
    {!! Form::label('date', __('cmsadmin::models/albums.fields.date')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $album->date }}</p>
</div>

<!-- Title Field -->
<div class="col-sm-12">
    {!! Form::label('title', __('cmsadmin::models/albums.fields.title')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $album->title }}</p>
</div>

<!-- Slug Field -->
<div class="col-sm-12">
    {!! Form::label('slug', __('cmsadmin::models/albums.fields.slug')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $album->slug }}</p>
</div>

<!-- Detail Field -->
<div class="col-sm-12">
    {!! Form::label('detail', __('cmsadmin::models/albums.fields.detail')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! nl2br($album->detail) ?? '' !!}</p>
</div>

<!-- Cover Image Id Field -->
<div class="col-sm-12">
    {!! Form::label('cover_image_id', __('cmsadmin::models/albums.fields.cover_image_id')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! renderFancyboxImage(ALBUM_FILE_DIR_NAME, $album->coverImage?->image_name, 200) !!}</p>
</div>

<!-- Image Count Field -->
<div class="col-sm-12">
    {!! Form::label('image_count', __('cmsadmin::models/albums.fields.image_count')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $album->image_count }}</p>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getPublishText($album->publish) }}</p>
    @else
        <p>
            {!! manageRenderBsSwitchGrid('albums.togglePublish', 'publish', $album->album_id, $album->publish, 'cmsadmin.albums.togglePublish') !!}
        </p>
    @endif
</div>

<!-- Reserved Field -->
<div class="col-sm-12">
    {!! Form::label('reserved', __('common::crud.fields.reserved')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getReservedText($album->reserved) }}</p>
    @else
        <p>
            {!! manageRenderBsSwitchGrid('albums.toggleReserved', 'reserved', $album->album_id, $album->reserved, 'cmsadmin.albums.toggleReserved') !!}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $album->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $album->created_by ? getUserDataById($album->created_by) : '' }}</p>
</div>

@if ($album->updated_at)
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $album->updated_at }}</p>
    </div>
@endif

@if ($album->updated_by)
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataById($album->updated_by) }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($album->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $album->deleted_at }}</p>
        </div>
    @endif

    @if ($album->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($album->deleted_by) }}</p>
        </div>
    @endif
@endif
