<!-- Faq Cat Name Field -->
<div class="col-sm-12">
    {!! Form::label('faq_cat_name', __('cmsadmin::models/faq_categories.fields.faq_cat_name')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $faqCategory->faq_cat_name }}</p>
</div>

<!-- Slug Field -->
<div class="col-sm-12">
    {!! Form::label('slug', __('cmsadmin::models/faq_categories.fields.slug')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $faqCategory->slug }}</p>
</div>

<!-- Faq Cat Image Field -->
<div class="col-sm-12">
    {!! Form::label('faq_cat_image', __('cmsadmin::models/faq_categories.fields.faq_cat_image')) !!}
    <span class="semicolon mr-3">:</span>
    <div class="d-flex align-items-end">
        <p>
            @if (!empty($faqCategory->faq_cat_image))
                <div class="mr-1">
                    {!! renderFancyboxImage(FAQ_CATEGORY_FILE_DIR_NAME, $faqCategory->faq_cat_image, IMAGE_WIDTH_200) !!}
                </div>
                @if ($mode !== 'trash-restore')
                    {!! renderImageDeleteIcon(route('cmsadmin.faqCategories.removeImage', [$faqCategory->faq_cat_id]), 'faq_cat_image') !!}
                @endif
            @endif
        </p>
    </div>
</div>

<!-- Remarks Field -->
<div class="col-sm-12">
    {!! Form::label('remarks', __('cmsadmin::models/faq_categories.fields.remarks')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! nl2br($faqCategory->remarks) ?? '' !!}</p>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getPublishText($faqCategory->publish) }}</p>
    @else
        <p>
            {!! manageRenderBsSwitchGrid(
                'faqCategories.togglePublish',
                'publish',
                $faqCategory->faq_cat_id,
                $faqCategory->publish,
                'cmsadmin.faqCategories.togglePublish',
            ) !!}
        </p>
    @endif
</div>

<!-- Reserved Field -->
<div class="col-sm-12">
    {!! Form::label('reserved', __('common::crud.fields.reserved')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getReservedText($faqCategory->reserved) }}</p>
    @else
        <p>
            {!! manageRenderBsSwitchGrid(
                'faqCategories.toggleReserved',
                'reserved',
                $faqCategory->faq_cat_id,
                $faqCategory->reserved,
                'cmsadmin.faqCategories.toggleReserved',
            ) !!}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $faqCategory->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $faqCategory->created_by ? getUserDataById($faqCategory->created_by) : '' }}</p>
</div>

@if (!empty($faqCategory->updated_at))
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $faqCategory->updated_at }}</p>
    </div>
@endif

@if (!empty($faqCategory->updated_by))
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataById($faqCategory->updated_by) }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($faqCategory->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $faqCategory->deleted_at }}</p>
        </div>
    @endif

    @if ($faqCategory->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($faqCategory->deleted_by) }}</p>
        </div>
    @endif
@endif
