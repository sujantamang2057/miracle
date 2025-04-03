<!-- Category Name Field -->
<div class="col-sm-12">
    {!! Form::label('category_name', __('cmsadmin::models/post_categories.fields.category_name')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $postCategory->category_name }}</p>
</div>

<!-- Slug Field -->
<div class="col-sm-12">
    {!! Form::label('slug', __('cmsadmin::models/post_categories.fields.slug')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $postCategory->slug }}</p>
</div>

<!-- Parent Category Id Field -->
<div class="col-sm-12">
    {!! Form::label('parent_category_id', __('cmsadmin::models/post_categories.fields.parent_category_id')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $postCategory->parent->category_name ?? '' }} </p>
</div>

<!-- Category Image Field -->
<div class="col-sm-12">
    {!! Form::label('category_image', __('cmsadmin::models/post_categories.fields.category_image')) !!}
    <span class="semicolon mr-3">:</span>
    <div class="d-flex align-items-end">
        <p>
            @if (!empty($postCategory->category_image))
                <div class="mr-1">
                    {!! renderFancyboxImage(POST_CATEGORY_FILE_DIR_NAME, $postCategory->category_image, IMAGE_WIDTH_200) !!}
                </div>
                @if ($mode !== 'trash-restore')
                    {!! renderImageDeleteIcon(route('cmsadmin.postCategories.removeImage', [$postCategory->category_id]), 'category_image') !!}
                @endif
            @endif
        </p>
    </div>
</div>

<!-- Remarks Field -->
<div class="col-sm-12">
    {!! Form::label('remarks', __('cmsadmin::models/post_categories.fields.remarks')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! nl2br($postCategory->remarks) ?? '' !!}</p>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getPublishText($postCategory->publish) }}</p>
    @else
        <p>{!! manageRenderBsSwitchGrid(
            'postCategories.togglePublish',
            'publish',
            $postCategory->category_id,
            $postCategory->publish,
            'cmsadmin.postCategories.togglePublish',
        ) !!}
        </p>
    @endif
</div>

<!-- Reserved Field -->
<div class="col-sm-12">
    {!! Form::label('reserved', __('common::crud.fields.reserved')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getReservedText($postCategory->reserved) }}</p>
    @else
        <p>{!! manageRenderBsSwitchGrid(
            'postCategories.toggleReserved',
            'reserved',
            $postCategory->category_id,
            $postCategory->reserved,
            'cmsadmin.postCategories.toggleReserved',
        ) !!}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $postCategory->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $postCategory->created_by ? getUserDataById($postCategory->created_by) : '' }}</p>
</div>

@if (!empty($postCategory->updated_at))
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $postCategory->updated_at }}</p>
    </div>
@endif

@if (!empty($postCategory->updated_by))
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataById($postCategory->updated_by) }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($postCategory->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $postCategory->deleted_at }}</p>
        </div>
    @endif

    @if ($postCategory->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($postCategory->deleted_by) }}</p>
        </div>
    @endif
@endif
