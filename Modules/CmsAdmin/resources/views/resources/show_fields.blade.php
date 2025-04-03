<!-- File Name Field -->
<div class="col-sm-12">
    {!! Form::label('file_name', __('cmsadmin::models/resources.fields.file_name')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $resource->file_name }}</p>
</div>

<!-- Display Name Field -->
<div class="col-sm-12">
    {!! Form::label('display_name', __('cmsadmin::models/resources.fields.display_name')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $resource->display_name }}</p>
</div>

<!-- File Size Field -->
<div class="col-sm-12">
    {!! Form::label('file_size', __('cmsadmin::models/resources.fields.file_size')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $resource->file_size }}</p>
</div>

<!-- File Type Field -->
<div class="col-sm-12">
    {!! Form::label('file_type', __('cmsadmin::models/resources.fields.file_type')) !!}
    <span class="semicolon mr-3">:</span>
    <p>
        <a href="{{ url('storage/' . RESOURCE_FILE_DIR_NAME . '/' . $resource->file_name) }}"
            download="{{ $resource->display_name . '.' . $resource->file_type }}">{!! renderFileIcon($resource->file_name) !!}</a>
    </p>
</div>

<!-- Download Count Field -->
<div class="col-sm-12">
    {!! Form::label('download_count', __('cmsadmin::models/resources.fields.download_count')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $resource->download_count }}</p>
</div>

<!-- View Count Field -->
<div class="col-sm-12">
    {!! Form::label('view_count', __('cmsadmin::models/resources.fields.view_count')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $resource->view_count }}</p>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getPublishText($resource->publish) }}</p>
    @else
        <p>
            {!! manageRenderBsSwitchGrid(
                'resources.togglePublish',
                'publish',
                $resource->resource_id,
                $resource->publish,
                'cmsadmin.resources.togglePublish',
            ) !!}
        </p>
    @endif
</div>

<!-- Reserved Field -->
<div class="col-sm-12">
    {!! Form::label('reserved', __('common::crud.fields.reserved')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getReservedText($resource->reserved) }}</p>
    @else
        <p>
            {!! manageRenderBsSwitchGrid(
                'resources.toggleReserved',
                'reserved',
                $resource->resource_id,
                $resource->reserved,
                'cmsadmin.resources.toggleReserved',
            ) !!}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $resource->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $resource->created_by ? getUserDataById($resource->created_by) : '' }}</p>
</div>

@if ($resource->updated_at)
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $resource->updated_at }}</p>
    </div>
@endif

@if ($resource->updated_by)
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataById($resource->updated_by) }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($resource->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $resource->deleted_at }}</p>
        </div>
    @endif

    @if ($resource->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($resource->deleted_by) }}</p>
        </div>
    @endif
@endif
