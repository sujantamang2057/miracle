<!-- Title Field -->
<div class="col-sm-12">
    {!! Form::label('title', __('sys::models/sns.fields.title')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $sns->title }}</p>
</div>

<!-- Icon Field -->
<div class="col-sm-12">
    {!! Form::label('icon', __('sys::models/sns.fields.icon')) !!}
    <span class="semicolon mr-3">:</span>
    <div class="d-flex align-items-end">
        @if (!empty($sns->icon))
            <div class="mr-1">{!! renderFancyboxImage(SNS_FILE_DIR_NAME, $sns->icon, IMAGE_WIDTH_100, IMAGE_WIDTH_200) !!}</div>
            {!! renderImageDeleteIcon(route('sys.sns.removeImage', [$sns->sns_id]), 'icon') !!}
        @endif
    </div>
</div>

<!-- Class Field -->
<div class="col-sm-12">
    {!! Form::label('class', __('sys::models/sns.fields.class')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $sns->class }}</p>
</div>

<!-- Url Field -->
<div class="col-sm-12">
    {!! Form::label('url', __('sys::models/sns.fields.url')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $sns->url }}</p>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    <p>
        {!! manageRenderBsSwitchGrid('sns.togglePublish', 'publish', $sns->sns_id, $sns->publish, 'sys.sns.togglePublish') !!}
</div>

<!-- Reserved Field -->
<div class="col-sm-12">
    {!! Form::label('reserved', __('common::crud.fields.reserved')) !!}
    <span class="semicolon mr-3">:</span>
    <p>
        {!! manageRenderBsSwitchGrid('sns.toggleReserved', 'reserved', $sns->sns_id, $sns->reserved, 'sys.sns.toggleReserved') !!}
    </p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $sns->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ getUserDataById($sns->created_by) }}</p>
</div>

@if ($sns->updated_at)
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $sns->updated_at }}</p>
    </div>
@endif

@if ($sns->updated_by)
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataById($sns->updated_by) }}</p>
    </div>
@endif
