<!-- Cat Title Field -->
<div class="col-sm-12">
    {!! Form::label('cat_title', __('cmsadmin::models/blog_categories.fields.cat_title')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $blogCategory->cat_title }}</p>
</div>

<!-- Cat Slug Field -->
<div class="col-sm-12">
    {!! Form::label('cat_slug', __('cmsadmin::models/blog_categories.fields.cat_slug')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $blogCategory->cat_slug }}</p>
</div>

<!-- Cat Image Field -->
<div class="col-sm-12">
    {!! Form::label('cat_image', __('cmsadmin::models/blog_categories.fields.cat_image')) !!}
    <span class="semicolon mr-3">:</span>
    <div class="d-flex align-items-end">
        <p>
            @if (!empty($blogCategory->cat_image))
                <div class="mr-1">
                    {!! renderFancyboxImage(BLOG_CATEGORY_FILE_DIR_NAME, $blogCategory->cat_image, IMAGE_WIDTH_200) !!}
                </div>
                @if ($mode !== 'trash-restore')
                    {!! renderImageDeleteIcon(route('cmsadmin.blogCategories.removeImage', [$blogCategory->cat_id]), 'cat_image') !!}
                @endif
            @endif
        </p>
    </div>
</div>

<!-- Remarks Field -->
<div class="col-sm-12">
    {!! Form::label('remarks', __('cmsadmin::models/blog_categories.fields.remarks')) !!}
    <span class="semicolon mr-3">:</span>
    <pre>{{ $blogCategory->remarks ?? '' }}</pre>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getPublishText($blogCategory->publish) }}</p>
    @else
        <p>{!! manageRenderBsSwitchGrid(
            'blogCategories.togglePublish',
            'publish',
            $blogCategory->cat_id,
            $blogCategory->publish,
            'cmsadmin.blogCategories.togglePublish',
        ) !!}
        </p>
    @endif
</div>

<!-- Reserved Field -->
<div class="col-sm-12">
    {!! Form::label('reserved', __('common::crud.fields.reserved')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getReservedText($blogCategory->reserved) }}</p>
    @else
        <p>{!! manageRenderBsSwitchGrid(
            'blogCategories.toggleReserved',
            'reserved',
            $blogCategory->cat_id,
            $blogCategory->reserved,
            'cmsadmin.blogCategories.toggleReserved',
        ) !!}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $blogCategory->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $blogCategory->created_by ? getUserDataById($blogCategory->created_by) : '' }}</p>
</div>

@if ($blogCategory->updated_at)
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $blogCategory->updated_at }}</p>
    </div>
@endif

@if ($blogCategory->updated_by)
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $blogCategory->updated_by ? getUserDataById($blogCategory->updated_by) : '' }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($blogCategory->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $blogCategory->deleted_at }}</p>
        </div>
    @endif

    @if ($blogCategory->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($blogCategory->deleted_by) }}</p>
        </div>
    @endif
@endif
