<!-- Page Title Field -->
<div class="col-sm-12">
    {!! Form::label('page_title', __('cmsadmin::models/pages.fields.page_title')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $page->page_title }}</p>
</div>

<!-- Slug Field -->
<div class="col-sm-12">
    {!! Form::label('slug', __('cmsadmin::models/pages.fields.slug')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $page->slug }}</p>
</div>

<!-- Page Type Field -->
<div class="col-sm-12">
    {!! Form::label('page_type', __('cmsadmin::models/pages.fields.page_type')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ getPageTypeText($page->page_type) }}</p>
</div>

<!-- Page File Name Field -->
<div class="col-sm-12">
    {!! Form::label('page_file_name', __('cmsadmin::models/pages.fields.page_file_name')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $page->page_file_name }}</p>
</div>

<!-- Meta Keyword Field -->
<div class="col-sm-12">
    {!! Form::label('meta_keyword', __('cmsadmin::models/pages.fields.meta_keyword')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $page->meta_keyword }}</p>
</div>

<!-- Meta Description Field -->
<div class="col-sm-12">
    {!! Form::label('meta_description', __('cmsadmin::models/pages.fields.meta_description')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $page->meta_description }}</p>
</div>

<!-- Description Field -->
<div class="col-sm-12">
    {!! Form::label('description', __('cmsadmin::models/pages.fields.description')) !!}
    <span class="semicolon mr-3">:</span>

    @if ($page->page_type == '1')
        <pre>{!! $page->description !!} </pre>
    @else
        <pre>{{ nl2br($page->description ?? '') }} </pre>
    @endif
</div>

<!-- Banner Image Field -->
<div class="col-sm-12">
    {!! Form::label('banner_image', __('cmsadmin::models/pages.fields.banner_image')) !!}
    <span class="semicolon mr-3">:</span>
    <div class="d-flex align-items-end">
        <p>
            @if (!empty($page->banner_image))
                <div class="mr-1">
                    {!! renderFancyboxImage(PAGE_FILE_DIR_NAME, $page->banner_image, IMAGE_WIDTH_200) !!}
                </div>
                @if ($mode !== 'trash-restore')
                    {!! renderImageDeleteIcon(route('cmsadmin.pages.removeImage', [$page->page_id]), 'banner_image') !!}
                @endif
            @endif
        </p>
    </div>
</div>

<!-- Published Date Field -->
<div class="col-sm-12">
    {!! Form::label('published_date', __('common::crud.fields.published_date')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $page->published_date }}</p>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getPublishText($page->publish) }}</p>
    @else
        <p>{!! manageRenderBsSwitchGrid('pages.togglePublish', 'publish', $page->page_id, $page->publish, 'cmsadmin.pages.togglePublish') !!}
        </p>
    @endif
</div>

<!-- Reserved Field -->
<div class="col-sm-12">
    {!! Form::label('reserved', __('common::crud.fields.reserved')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getReservedText($page->reserved) }}</p>
    @else
        <p>{!! manageRenderBsSwitchGrid('pages.toggleReserved', 'reserved', $page->page_id, $page->reserved, 'cmsadmin.pages.toggleReserved') !!}
        </p>
    @endif
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $page->created_at }}</p>
</div>

<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $page->created_by ? getUserDataById($page->created_by) : '' }}</p>
</div>

<!-- Updated At Field -->
@if ($page->updated_at)
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $page->updated_at }}</p>
    </div>
@endif

<!-- Updated By Field -->
@if ($page->updated_by)
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataById($page->updated_by) }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($page->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $page->deleted_at }}</p>
        </div>
    @endif

    @if ($page->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($page->deleted_by) }}</p>
        </div>
    @endif
@endif
