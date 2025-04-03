<!-- Caption Field -->
<div class="col-sm-12">
    {!! Form::label('caption', __('cmsadmin::models/video_galleries.fields.caption')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $videoGallery->caption }}</p>
</div>

<!-- Video Url Field -->
<div class="col-sm-12">
    {!! Form::label('video_url', __('cmsadmin::models/video_galleries.fields.video_url')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $videoGallery->video_url }}</p>
</div>

<!-- Details Field -->
<div class="col-sm-12">
    {!! Form::label('details', __('cmsadmin::models/video_galleries.fields.details')) !!}
    <span class="semicolon mr-3">:</span>
    <pre>{{ $videoGallery->details ?? '' }}</pre>
</div>

<!-- Feature Image Field -->
<div class="col-sm-12">
    {!! Form::label('feature_image', __('cmsadmin::models/video_galleries.fields.feature_image')) !!}
    <span class="semicolon mr-3">:</span>
    <div class="d-flex align-items-end">
        <p>
            @if (!empty($videoGallery->feature_image))
                <div class="mr-1">
                    {!! renderFancyboxImage(VIDEO_ALBUM_FILE_DIR_NAME, $videoGallery->feature_image, IMAGE_WIDTH_200) !!}
                </div>
                {!! renderImageDeleteIcon(route('cmsadmin.videoGalleries.removeImage', [$videoGallery->video_id]), 'feature_image') !!}
            @endif
        </p>
    </div>
    </p>
</div>

<!-- Published Date Field -->
<div class="col-sm-12">
    {!! Form::label('published_date', __('common::crud.fields.published_date')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $videoGallery->published_date }}</p>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    <p>
        {!! manageRenderBsSwitchGrid(
            'videoGalleries.togglePublish',
            'publish',
            $videoGallery->video_id,
            $videoGallery->publish,
            'cmsadmin.videoGalleries.togglePublish',
            [
                'videoAlbum' => $videoGallery->album_id,
            ],
        ) !!}
    </p>
</div>

<!-- Reserved Field -->
<div class="col-sm-12">
    {!! Form::label('reserved', __('common::crud.fields.reserved')) !!}
    <span class="semicolon mr-3">:</span>
    </p>
    {!! manageRenderBsSwitchGrid(
        'videoGalleries.toggleReserved',
        'reserved',
        $videoGallery->video_id,
        $videoGallery->reserved,
        'cmsadmin.videoGalleries.toggleReserved',
        [
            'videoAlbum' => $videoGallery->album_id,
        ],
    ) !!}
    </p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $videoGallery->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $videoGallery->created_by ? getUserDataById($videoGallery->created_by) : '' }}</p>
</div>

@if ($videoGallery->updated_at)
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $videoGallery->updated_at }}</p>
    </div>
@endif

@if ($videoGallery->updated_by)
    <!-- UpdatedBy Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataById($videoGallery->updated_by) }}</p>
    </div>
@endif
