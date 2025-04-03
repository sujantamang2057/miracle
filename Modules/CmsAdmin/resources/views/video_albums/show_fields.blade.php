<!-- Album Name Field -->
<div class="col-sm-12">
    {!! Form::label('album_name', __('cmsadmin::models/video_albums.fields.album_name')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $videoAlbum->album_name }}</p>
</div>

<!-- Slug Field -->
<div class="col-sm-12">
    {!! Form::label('slug', __('cmsadmin::models/video_albums.fields.slug')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $videoAlbum->slug }}</p>
</div>

<!-- Album Date Field -->
<div class="col-sm-12">
    {!! Form::label('album_date', __('cmsadmin::models/video_albums.fields.album_date')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $videoAlbum->album_date }}</p>
</div>

<!-- Description Field -->
<div class="col-sm-12">
    {!! Form::label('description', __('cmsadmin::models/video_albums.fields.description')) !!}
    <span class="semicolon mr-3">:</span>
    <pre>{{ $videoAlbum->description ?? '' }}</pre>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getPublishText($videoAlbum->publish) }}</p>
    @else
        <p>{!! manageRenderBsSwitchGrid(
            'videoAlbums.togglePublish',
            'publish',
            $videoAlbum->album_id,
            $videoAlbum->publish,
            'cmsadmin.videoAlbums.togglePublish',
        ) !!}
        </p>
    @endif
</div>

<!-- Reserved Field -->
<div class="col-sm-12">
    {!! Form::label('reserved', __('common::crud.fields.reserved')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getReservedText($videoAlbum->reserved) }}</p>
    @else
        <p>{!! manageRenderBsSwitchGrid(
            'videoAlbums.toggleReserved',
            'reserved',
            $videoAlbum->album_id,
            $videoAlbum->reserved,
            'cmsadmin.videoAlbums.toggleReserved',
        ) !!}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $videoAlbum->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $videoAlbum->created_by ? getUserDataById($videoAlbum->created_by) : '' }}</p>
</div>

@if ($videoAlbum->updated_at)
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $videoAlbum->updated_at }}</p>
    </div>
@endif

@if ($videoAlbum->updated_by)
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataById($videoAlbum->updated_by) }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($videoAlbum->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $videoAlbum->deleted_at }}</p>
        </div>
    @endif

    @if ($videoAlbum->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($videoAlbum->deleted_by) }}</p>
        </div>
    @endif
@endif
