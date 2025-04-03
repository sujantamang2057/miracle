<!-- Block Name Field -->
<div class="col-sm-12">
    {!! Form::label('block_name', __('cmsadmin::models/blocks.fields.block_name')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $block->block_name }}</p>
</div>

<!-- Filename Field -->
<div class="col-sm-12">
    {!! Form::label('filename', __('cmsadmin::models/blocks.fields.filename')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $block->filename }}</p>
</div>

<!-- File Contents Field -->
<div class="col-sm-12">
    {!! Form::label('file_contents', __('cmsadmin::models/blocks.fields.file_contents')) !!}
    <span class="semicolon mr-3">:</span>
    <p>
        <pre>{{ $block->file_contents }}</pre>
    </p>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getPublishText($block->publish) }}</p>
    @else
        <p>{!! manageRenderBsSwitchGrid('blocks.togglePublish', 'publish', $block->block_id, $block->publish, 'cmsadmin.blocks.togglePublish') !!}
        </p>
    @endif
</div>

<!-- Reserved Field -->
<div class="col-sm-12">
    {!! Form::label('reserved', __('common::crud.fields.reserved')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getReservedText($block->reserved) }}</p>
    @else
        <p>{!! manageRenderBsSwitchGrid('blocks.toggleReserved', 'reserved', $block->block_id, $block->reserved, 'cmsadmin.blocks.toggleReserved') !!}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $block->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $block->created_by ? getUserDataById($block->created_by) : '' }}</p>
</div>

@if ($block->updated_at)
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $block->updated_at }}</p>
    </div>
@endif

@if ($block->updated_by)
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataById($block->updated_by) }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($block->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $block->deleted_at }}</p>
        </div>
    @endif

    @if ($block->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($block->deleted_by) }}</p>
        </div>
    @endif
@endif
