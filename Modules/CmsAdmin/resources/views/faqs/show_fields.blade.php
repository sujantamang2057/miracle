<!-- Faq Cat Id Field -->
<div class="col-sm-12">
    {!! Form::label('faq_cat_id', __('cmsadmin::models/faqs.fields.faq_cat_id')) !!}
    <span class="semicolon mr-3">:</span>
    <p>
        @if (!@empty($faq->faq_cat_id) && !empty($faq->faqCat))
            {{ $faq->faqCat->faq_cat_name }}
        @else
            {!! ' ' !!}
        @endif
    </p>
</div>

<!-- Question Field -->
<div class="col-sm-12">
    {!! Form::label('question', __('cmsadmin::models/faqs.fields.question')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $faq->question }}</p>
</div>

<!-- Answer Field -->
<div class="col-sm-12">
    {!! Form::label('answer', __('cmsadmin::models/faqs.fields.answer')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! nl2br($faq->answer) ?? '' !!}</p>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getPublishText($faq->publish) }}</p>
    @else
        <p>
            {!! manageRenderBsSwitchGrid('faqs.togglePublish', 'publish', $faq->faq_id, $faq->publish, 'cmsadmin.faqs.togglePublish') !!}
        </p>
    @endif
</div>

<!-- Reserved Field -->
<div class="col-sm-12">
    {!! Form::label('reserved', __('common::crud.fields.reserved')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getReservedText($faq->reserved) }}</p>
    @else
        <p>
            {!! manageRenderBsSwitchGrid('faqs.toggleReserved', 'reserved', $faq->faq_id, $faq->reserved, 'cmsadmin.faqs.toggleReserved') !!}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $faq->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $faq->created_by ? getUserDataByID($faq->created_by) : '' }}</p>
</div>

@if ($faq->updated_at)
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $faq->updated_at }}</p>
    </div>
@endif

@if ($faq->updated_by)
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataByID($faq->updated_by) }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($faq->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $faq->deleted_at }}</p>
        </div>
    @endif

    @if ($faq->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($faq->deleted_by) }}</p>
        </div>
    @endif
@endif
