<!-- Module Name Field -->
<div class="col-sm-12">
    {!! Form::label('module_name', __('cmsadmin::models/seos.fields.module_name')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $seo->module_name }}</p>
</div>

<!-- Code Field -->
<div class="col-sm-12">
    {!! Form::label('code', __('cmsadmin::models/seos.fields.code')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $seo->code }}</p>
</div>

<!-- Title Field -->
<div class="col-sm-12">
    {!! Form::label('title', __('cmsadmin::models/seos.fields.title')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! nl2br($seo->title) !!}</p>
</div>

<!-- Title Locale Field -->
<div class="col-sm-12">
    {!! Form::label('title_locale', __('cmsadmin::models/seos.fields.title_locale')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! nl2br($seo->title_locale) !!}</p>
</div>

<!-- Keyword Field -->
<div class="col-sm-12">
    {!! Form::label('keyword', __('cmsadmin::models/seos.fields.keyword')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! nl2br($seo->keyword) !!}</p>
</div>

<!-- Keyword Locale Field -->
<div class="col-sm-12">
    {!! Form::label('keyword_locale', __('cmsadmin::models/seos.fields.keyword_locale')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! nl2br($seo->keyword_locale) !!}</p>
</div>

<!-- Description Field -->
<div class="col-sm-12">
    {!! Form::label('description', __('cmsadmin::models/seos.fields.description')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! nl2br($seo->description) !!}</p>
</div>

<!-- Description Locale Field -->
<div class="col-sm-12">
    {!! Form::label('description_locale', __('cmsadmin::models/seos.fields.description_locale')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! nl2br($seo->description_locale) !!}</p>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getPublishText($seo->publish) }}</p>
    @else
        <p>
            {!! manageRenderBsSwitchGrid('seos.togglePublish', 'publish', $seo->id, $seo->publish, 'cmsadmin.seos.togglePublish') !!}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $seo->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $seo->created_by ? getUserDataByID($seo->created_by) : '' }}</p>
</div>

@if ($seo->updated_at)
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $seo->updated_at }}</p>
    </div>
@endif

@if ($seo->updated_by)
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataByID($seo->updated_by) }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($seo->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $seo->deleted_at }}</p>
        </div>
    @endif

    @if ($seo->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($seo->deleted_by) }}</p>
        </div>
    @endif
@endif
