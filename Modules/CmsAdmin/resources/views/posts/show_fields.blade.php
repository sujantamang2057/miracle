<!-- Post Title Field -->
<div class="col-sm-12">
    {!! Form::label('post_title', __('cmsadmin::models/posts.fields.post_title')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $post->post_title }}</p>
</div>

<!-- Slug Field -->
<div class="col-sm-12">
    {!! Form::label('slug', __('cmsadmin::models/posts.fields.slug')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $post->slug }}</p>
</div>

<!-- Category Id Field -->
<div class="col-sm-12">
    {!! Form::label('category_id', __('cmsadmin::models/posts.fields.category_id')) !!}
    <span class="semicolon mr-3">:</span>
    <p>
        @if (!@empty($post->category_id) && !empty($post->category))
            {{ $post->category->category_name }}
        @endif
    </p>
</div>

<!-- Description Field -->
<div class="col-sm-12">
    {!! Form::label('description', __('cmsadmin::models/posts.fields.description')) !!}
    <span class="semicolon mr-3">:</span>
    <pre>{!! $post->description !!}</pre>
</div>

<!-- Banner Image Field -->
<div class="col-sm-12">
    {!! Form::label('banner_image', __('cmsadmin::models/posts.fields.banner_image')) !!}
    <span class="semicolon mr-3">:</span>
    <div class="d-flex align-items-end">
        <p>
            @if (!empty($post->banner_image))
                <div class="mr-1">
                    {!! renderFancyboxImage(POST_FILE_DIR_NAME, $post->banner_image, IMAGE_WIDTH_200) !!}
                </div>
                @if ($mode !== 'trash-restore')
                    {!! renderImageDeleteIcon(route('cmsadmin.posts.removeImage', [$post->post_id]), 'banner_image') !!}
                @endif
            @endif
        </p>
    </div>
</div>

<!-- Feature Image Field -->
<div class="col-sm-12">
    {!! Form::label('feature_image', __('cmsadmin::models/posts.fields.feature_image')) !!}
    <span class="semicolon mr-3">:</span>
    <div class="d-flex align-items-end">
        <p>
            @if (!empty($post->feature_image))
                <div class="mr-1">
                    {!! renderFancyboxImage(POST_FILE_DIR_NAME, $post->feature_image, IMAGE_WIDTH_200) !!}
                </div>
                @if ($mode !== 'trash-restore')
                    {!! renderImageDeleteIcon(route('cmsadmin.posts.removeImage', [$post->post_id]), 'feature_image') !!}
                @endif
            @endif
        </p>
    </div>
</div>

<!-- Publish Date From Field -->
<div class="col-sm-12">
    {!! Form::label('publish_date_from', __('common::crud.fields.publish_date_from')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $post->publish_date_from }}</p>
</div>

<!-- Publish Date To Field -->
<div class="col-sm-12">
    {!! Form::label('publish_date_to', __('common::crud.fields.publish_date_to')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $post->publish_date_to }}</p>
</div>

<!-- Published Date Field -->
<div class="col-sm-12">
    {!! Form::label('published_date', __('common::crud.fields.published_date')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $post->published_date }}</p>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getPublishText($post->publish) }}</p>
    @else
        <p>{!! manageRenderBsSwitchGrid('posts.togglePublish', 'publish', $post->post_id, $post->publish, 'cmsadmin.posts.togglePublish') !!}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $post->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $post->created_by ? getUserDataById($post->created_by) : '' }}</p>
</div>

@if (!empty($post->updated_at))
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $post->updated_at }}</p>
    </div>
@endif

@if (!empty($post->updated_by))
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataById($post->updated_by) }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($post->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $post->deleted_at }}</p>
        </div>
    @endif

    @if ($post->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($post->deleted_by) }}</p>
        </div>
    @endif
@endif
