<!-- Tm Name Field -->
<div class="col-sm-12">
    {!! Form::label('tm_name', __('cmsadmin::models/testimonials.fields.tm_name')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $testimonial->tm_name }}</p>
</div>

<!-- Tm Email Field -->
<div class="col-sm-12">
    {!! Form::label('tm_email', __('cmsadmin::models/testimonials.fields.tm_email')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $testimonial->tm_email }}</p>
</div>

<!-- Tm Profile  Field -->
<div class="col-sm-12">
    {!! Form::label('tm_profile_image', __('cmsadmin::models/testimonials.fields.tm_profile_image')) !!}
    <span class="semicolon mr-3">:</span>
    <div class="d-flex align-items-end">
        @if (!empty($testimonial->tm_profile_image))
            <div class="mr-1">
                {!! renderFancyboxImage(TESTIMONIAL_FILE_DIR_NAME, $testimonial->tm_profile_image, IMAGE_WIDTH_200) !!}
            </div>
            @if ($mode !== 'trash-restore')
                {!! renderImageDeleteIcon(route('cmsadmin.testimonials.removeImage', [$testimonial->testimonial_id]), 'tm_profile_image') !!}
            @endif
        @endif
    </div>
</div>

<!-- Tm Company Field -->
<div class="col-sm-12">
    {!! Form::label('tm_company', __('cmsadmin::models/testimonials.fields.tm_company')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $testimonial->tm_company }}</p>
</div>

<!-- Tm Designation Field -->
<div class="col-sm-12">
    {!! Form::label('tm_designation', __('cmsadmin::models/testimonials.fields.tm_designation')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $testimonial->tm_designation }}</p>
</div>

<!-- Tm Testimonial Field -->
<div class="col-sm-12">
    {!! Form::label('tm_testimonial', __('cmsadmin::models/testimonials.fields.tm_testimonial')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! nl2br($testimonial->tm_testimonial) ?? '' !!}</p>
</div>

<!-- Sns Fb Field -->
<div class="col-sm-12">
    {!! Form::label('sns_fb', __('cmsadmin::models/testimonials.fields.sns_fb')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $testimonial->sns_fb }}</p>
</div>

<!-- Sns Linkedin Field -->
<div class="col-sm-12">
    {!! Form::label('sns_linkedin', __('cmsadmin::models/testimonials.fields.sns_linkedin')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $testimonial->sns_linkedin }}</p>
</div>

<!-- Sns Twitter Field -->
<div class="col-sm-12">
    {!! Form::label('sns_twitter', __('cmsadmin::models/testimonials.fields.sns_twitter')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $testimonial->sns_twitter }}</p>
</div>

<!-- Sns Instagram Field -->
<div class="col-sm-12">
    {!! Form::label('sns_instagram', __('cmsadmin::models/testimonials.fields.sns_instagram')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $testimonial->sns_instagram }}</p>
</div>

<!-- Sns Youtube Field -->
<div class="col-sm-12">
    {!! Form::label('sns_youtube', __('cmsadmin::models/testimonials.fields.sns_youtube')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $testimonial->sns_youtube }}</p>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getPublishText($testimonial->publish) }}</p>
    @else
        <p>
            {!! manageRenderBsSwitchGrid(
                'testimonials.togglePublish',
                'publish',
                $testimonial->testimonial_id,
                $testimonial->publish,
                'cmsadmin.testimonials.togglePublish',
            ) !!}
        </p>
    @endif
</div>

<!-- Reserved Field -->
<div class="col-sm-12">
    {!! Form::label('reserved', __('common::crud.fields.reserved')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getReservedText($testimonial->reserved) }}</p>
    @else
        <p>
            {!! manageRenderBsSwitchGrid(
                'testimonials.toggleReserved',
                'reserved',
                $testimonial->testimonial_id,
                $testimonial->reserved,
                'cmsadmin.testimonials.toggleReserved',
            ) !!}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $testimonial->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $testimonial->created_by ? getUserDataByID($testimonial->created_by) : '' }}</p>
</div>

@if ($testimonial->updated_by)
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $testimonial->updated_at }}</p>
    </div>
@endif

@if ($testimonial->updated_by)
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataByID($testimonial->updated_by) }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($testimonial->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $testimonial->deleted_at }}</p>
        </div>
    @endif

    @if ($testimonial->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($testimonial->deleted_by) }}</p>
        </div>
    @endif
@endif
