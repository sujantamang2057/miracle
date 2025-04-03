<!-- Category Name Field -->
<div class="col-sm-12">
    {!! Form::label('category_name', __('cmsadmin::models/news_categories.fields.category_name')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $newsCategory->category_name }}</p>
</div>

<!-- Parent Category Id Field -->
<div class="col-sm-12">
    {!! Form::label('parent_category_id', __('cmsadmin::models/news_categories.fields.parent_category_id')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $newsCategory->parent->category_name ?? '' }}</p>
</div>

<!-- Slug Field -->
<div class="col-sm-12">
    {!! Form::label('slug', __('cmsadmin::models/news_categories.fields.slug')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $newsCategory->slug }}</p>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getPublishText($newsCategory->publish) }}</p>
    @else
        <p>
            {!! manageRenderBsSwitchGrid(
                'newsCategories.togglePublish',
                'publish',
                $newsCategory->category_id,
                $newsCategory->publish,
                'cmsadmin.newsCategories.togglePublish',
            ) !!}
        </p>
    @endif
</div>

<!-- Reserved Field -->
<div class="col-sm-12">
    {!! Form::label('reserved', __('common::crud.fields.reserved')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getReservedText($newsCategory->reserved) }}</p>
    @else
        <p>
            {!! manageRenderBsSwitchGrid(
                'newsCategories.toggleReserved',
                'reserved',
                $newsCategory->category_id,
                $newsCategory->reserved,
                'cmsadmin.newsCategories.toggleReserved',
            ) !!}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $newsCategory->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $newsCategory->created_by ? getUserDataById($newsCategory->created_by) : '' }}</p>
</div>

@if (!empty($newsCategory->updated_at))
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $newsCategory->updated_at }}</p>
    </div>
@endif

@if (!empty($newsCategory->updated_by))
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataById($newsCategory->updated_by) }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($newsCategory->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $newsCategory->deleted_at }}</p>
        </div>
    @endif

    @if ($newsCategory->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($newsCategory->deleted_by) }}</p>
        </div>
    @endif
@endif
