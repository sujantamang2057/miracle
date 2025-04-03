<!-- News Title Field -->
<div class="col-sm-12">
    {!! Form::label('news_title', __('cmsadmin::models/news.fields.news_title')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $news->news_title }}</p>
</div>

<!-- Slug Field -->
<div class="col-sm-12">
    {!! Form::label('slug', __('cmsadmin::models/news.fields.slug')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $news->slug }}</p>
</div>

<!-- Category Id Field -->
<div class="col-sm-12">
    {!! Form::label('category_id', __('cmsadmin::models/news.fields.category_id')) !!}
    <span class="semicolon mr-3">:</span>
    <p>
        @if (!@empty($news->category_id) && !empty($news->category))
            {{ $news->category->category_name }}
        @endif
    </p>
</div>

<!-- Description Field -->
<div class="col-sm-12">
    {!! Form::label('description', __('cmsadmin::models/news.fields.description')) !!}
    <span class="semicolon mr-3">:</span>
    <pre>{!! $news->description !!}</pre>
</div>

<!-- Banner Image Field -->
<div class="col-sm-12">
    {!! Form::label('banner_image', __('cmsadmin::models/news.fields.banner_image')) !!}
    <span class="semicolon mr-3">:</span>
    <div class="d-flex align-items-end">
        <p>
            @if (!empty($news->banner_image))
                <div class="mr-1">
                    {!! renderFancyboxImage(NEWS_FILE_DIR_NAME, $news->banner_image, IMAGE_WIDTH_200) !!}
                </div>
                @if ($mode !== 'trash-restore')
                    {!! renderImageDeleteIcon(route('cmsadmin.news.removeImage', [$news->news_id]), 'banner_image') !!}
                @endif
            @endif
        </p>
    </div>
</div>

<!-- Feature Image Field -->
<div class="col-sm-12">
    {!! Form::label('feature_image', __('cmsadmin::models/news.fields.feature_image')) !!}
    <span class="semicolon mr-3">:</span>
    <div class="d-flex align-items-end">
        <p>
            @if (!empty($news->feature_image))
                <div class="mr-1">
                    {!! renderFancyboxImage(NEWS_FILE_DIR_NAME, $news->feature_image, IMAGE_WIDTH_200) !!}
                </div>
                @if ($mode !== 'trash-restore')
                    {!! renderImageDeleteIcon(route('cmsadmin.news.removeImage', [$news->news_id]), 'feature_image') !!}
                @endif
            @endif
        </p>
    </div>
</div>

<!-- Published Date Field -->
<div class="col-sm-12">
    {!! Form::label('published_date', __('common::crud.fields.published_date')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $news->published_date }}</p>
</div>

<!-- Publish Date From Field -->
<div class="col-sm-12">
    {!! Form::label('publish_date_from', __('common::crud.fields.publish_date_from')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $news->publish_date_from }}</p>
</div>

<!-- Publish Date To Field -->
<div class="col-sm-12">
    {!! Form::label('publish_date_to', __('common::crud.fields.publish_date_to')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $news->publish_date_to }}</p>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getPublishText($news->publish) }}</p>
    @else
        <p>
            {!! manageRenderBsSwitchGrid('news.togglePublish', 'publish', $news->news_id, $news->publish, 'cmsadmin.news.togglePublish') !!}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $news->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $news->created_by ? getUserDataById($news->created_by) : '' }}</p>
</div>

@if ($news->updated_at)
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $news->updated_at }}</p>
    </div>
@endif

@if ($news->updated_by)
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataById($news->updated_by) }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($news->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $news->deleted_at }}</p>
        </div>
    @endif

    @if ($news->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($news->deleted_by) }}</p>
        </div>
    @endif
@endif
