<!-- Cat Id Field -->
<div class="col-sm-12">
    {!! Form::label('cat_id', __('cmsadmin::models/blogs.fields.cat_id')) !!}
    <span class="semicolon mr-3">:</span>
    <p>
        @if (!@empty($blog->cat_id) && !empty($blog->cat))
            {{ $blog->cat->cat_title }}
        @else
            {!! ' ' !!}
        @endif
    </p>
</div>

<!-- Title Field -->
<div class="col-sm-12">
    {!! Form::label('title', __('cmsadmin::models/blogs.fields.title')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $blog->title }}</p>
</div>

<!-- Slug Field -->
<div class="col-sm-12">
    {!! Form::label('slug', __('cmsadmin::models/blogs.fields.slug')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $blog->slug }}</p>
</div>

<!-- Thumb Image Field -->
<div class="col-sm-12">
    {!! Form::label('thumb_image', __('cmsadmin::models/blogs.fields.thumb_image')) !!}
    <span class="semicolon mr-3">:</span>
    <div class="d-flex align-items-end">
        <p>
            @if (!empty($blog->thumb_image))
                <div class="mr-1">
                    {!! renderFancyboxImage(BLOG_FILE_DIR_NAME, $blog->thumb_image, IMAGE_WIDTH_200) !!}
                </div>
                @if ($mode !== 'trash-restore')
                    {!! renderImageDeleteIcon(route('cmsadmin.blogs.removeImage', [$blog->blog_id]), 'thumb_image') !!}
                @endif
            @endif
        </p>
    </div>
</div>

<!-- Image Field -->
<div class="col-sm-12">
    {!! Form::label('image', __('cmsadmin::models/blogs.fields.image')) !!}
    <span class="semicolon mr-3">:</span>
    <div class="d-flex align-items-end">
        <p>
            @if (!empty($blog->image))
                <div class="mr-1">
                    {!! renderFancyboxImage(BLOG_FILE_DIR_NAME, $blog->image, IMAGE_WIDTH_200) !!}
                </div>
                @if ($mode !== 'trash-restore')
                    {!! renderImageDeleteIcon(route('cmsadmin.blogs.removeImage', [$blog->blog_id]), 'image') !!}
                @endif
            @endif
        </p>
    </div>
</div>

<!-- Detail Field -->
<div class="col-sm-12">
    {!! Form::label('detail', __('cmsadmin::models/blogs.fields.detail')) !!}
    <span class="semicolon mr-3">:</span>
    <pre>{!! $blog->detail !!}</pre>
</div>

<!-- Video Url Field -->
<div class="col-sm-12">
    {!! Form::label('video_url', __('cmsadmin::models/blogs.fields.video_url')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $blog->video_url }}</p>
</div>

<!-- Display Date Field -->
<div class="col-sm-12">
    {!! Form::label('display_date', __('cmsadmin::models/blogs.fields.display_date')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $blog->display_date }}</p>
</div>

<!-- Publish From Field -->
<div class="col-sm-12">
    {!! Form::label('publish_from', __('common::crud.fields.publish_date_from')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $blog->publish_from }}</p>
</div>

<!-- Publish To Field -->
<div class="col-sm-12">
    {!! Form::label('publish_to', __('common::crud.fields.publish_date_to')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $blog->publish_to }}</p>
</div>

<!-- Remarks Field -->
<div class="col-sm-12">
    {!! Form::label('remarks', __('cmsadmin::models/blogs.fields.remarks')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! nl2br($blog->remarks ?? '') !!}</p>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getPublishText($blog->publish) }}</p>
    @else
        <p>{!! manageRenderBsSwitchGrid('blogs.togglePublish', 'publish', $blog->blog_id, $blog->publish, 'cmsadmin.blogs.togglePublish') !!}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $blog->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $blog->created_by ? getUserDataByID($blog->created_by) : '' }}</p>
</div>

@if ($blog->updated_at)
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $blog->updated_at }}</p>
    </div>
@endif

@if ($blog->updated_by)
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataByID($blog->updated_by) }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($blog->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $blog->deleted_at }}</p>
        </div>
    @endif

    @if ($blog->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($blog->deleted_by) }}</p>
        </div>
    @endif
@endif
