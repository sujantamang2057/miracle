<div class="col-sm-8">
    <!-- Tm Name Field -->
    <div class="form-group required {{ validationClass($errors->has('tm_name')) }}">
        {!! Form::label('tm_name', __('cmsadmin::models/testimonials.fields.tm_name') . ':') !!}
        {!! Form::text('tm_name', null, [
            'class' => 'form-control',
            'required',
            'maxlength' => 255,
            'autocomplete' => 'off',
        ]) !!}
        {{ validationMessage($errors->first('tm_name')) }}
    </div>

    <!-- Tm Email Field -->
    <div class="form-group required {{ validationClass($errors->has('tm_email')) }}">
        {!! Form::label('tm_email', __('cmsadmin::models/testimonials.fields.tm_email') . ':') !!}
        {!! Form::text('tm_email', null, [
            'class' => 'form-control',
            'required',
            'maxlength' => 100,
            'autocomplete' => 'off',
        ]) !!}
        {{ validationMessage($errors->first('tm_email')) }}
    </div>

    <!-- Tm Company Field -->
    <div class="form-group {{ validationClass($errors->has('tm_company')) }}">
        {!! Form::label('tm_company', __('cmsadmin::models/testimonials.fields.tm_company') . ':') !!}
        {!! Form::text('tm_company', null, ['class' => 'form-control', 'maxlength' => 255]) !!}
        {{ validationMessage($errors->first('tm_company')) }}
    </div>

    <!-- Tm Designation Field -->
    <div class="form-group {{ validationClass($errors->has('tm_designation')) }}">
        {!! Form::label('tm_designation', __('cmsadmin::models/testimonials.fields.tm_designation') . ':') !!}
        {!! Form::text('tm_designation', null, ['class' => 'form-control', 'maxlength' => 100]) !!}
        {{ validationMessage($errors->first('tm_designation')) }}
    </div>

    <!-- Tm Testimonial Field -->
    <div class="form-group required {{ validationClass($errors->has('tm_testimonial')) }}">
        {!! Form::label('tm_testimonial', __('cmsadmin::models/testimonials.fields.tm_testimonial') . ':') !!}
        {!! Form::textarea('tm_testimonial', null, [
            'class' => 'form-control',
            'required',
            'maxlength' => 65535,
        ]) !!}
        {{ validationMessage($errors->first('tm_testimonial')) }}
    </div>
</div>

<div class ="col-sm-4">
    <!-- Tm Profile Image Field -->
    <div class="form-group {{ validationClass($errors->has('tm_profile_image')) }}">
        {!! Form::label('tm_profile_image', __('cmsadmin::models/testimonials.fields.tm_profile_image') . ':') !!}
        <div class="clearfix"></div>
        {!! Form::hidden('tm_profile_image_pre', null) !!}
        {!! Form::file('tm_profile_image', [
            'id' => 'filepond1',
            'class' => 'my-pond',
            'value' => !empty($testimonial->tm_profile_image) ? $testimonial->tm_profile_image : null,
        ]) !!}
        <div class="image-help-text" id="basic-addon4"> {{ __('cmsadmin::models/testimonials.optimal_image_size') }}
        </div>
        {{ validationMessage($errors->first('tm_profile_image')) }}
        @if (old('tm_profile_image') && file_exists(storage_path('tmp/' . old('tm_profile_image'))))
            <p class="temp-image m-1">
                {!! Form::hidden('tm_profile_image', old('tm_profile_image'), [
                    'id' => 'filepond1-tm_profile_image',
                ]) !!}
                {!! renderTmpImage(old('tm_profile_image'), IMAGE_WIDTH_200) !!}
                {!! renderTmpImageDeleteIcon(old('tm_profile_image')) !!}
            </p>
        @elseif (!empty($testimonial->tm_profile_image))
            <p class="del-form-image m-1">
                {!! Form::hidden('tm_profile_image', !empty($testimonial->tm_profile_image) ? $testimonial->tm_profile_image : null, [
                    'id' => 'filepond1-tm_profile_image',
                ]) !!}
            <div class="d-flex align-items-end">
                <div class="mr-1">
                    {!! renderFancyboxImage(TESTIMONIAL_FILE_DIR_NAME, $testimonial->tm_profile_image, IMAGE_WIDTH_200) !!}
                </div>
                {!! renderImageDeleteIcon(route('cmsadmin.testimonials.removeImage', [$testimonial->testimonial_id]), 'tm_profile_image') !!}
            </div>
            </p>
        @endif
    </div>

    @push('page_scripts')
        <script>
            $(function() {
                var isMultiUpload = false,
                    moduleName = "testimonial",
                    upload_url = "{{ route('common.imageHandler.fileupload') }}",
                    delete_url = "{{ route('common.imageHandler.destroy') }}";
                initializeFilePond("filepond1", "tm_profile_image", moduleName, upload_url, delete_url, isMultiUpload);
            });
        </script>
    @endpush

    <!-- Sns Fb Field -->
    <div class="form-group {{ validationClass($errors->has('sns_fb')) }}">
        {!! Form::label('sns_fb', __('cmsadmin::models/testimonials.fields.sns_fb') . ':') !!}
        {!! Form::text('sns_fb', null, ['class' => 'form-control', 'maxlength' => 255]) !!}
        {{ validationMessage($errors->first('sns_fb')) }}
    </div>

    <!-- Sns Linkedin Field -->
    <div class="form-group {{ validationClass($errors->has('sns_linkedin')) }}">
        {!! Form::label('sns_linkedin', __('cmsadmin::models/testimonials.fields.sns_linkedin') . ':') !!}
        {!! Form::text('sns_linkedin', null, ['class' => 'form-control', 'maxlength' => 255]) !!}
        {{ validationMessage($errors->first('sns_linkedin')) }}
    </div>

    <!-- Sns Twitter Field -->
    <div class="form-group {{ validationClass($errors->has('sns_twitter')) }}">
        {!! Form::label('sns_twitter', __('cmsadmin::models/testimonials.fields.sns_twitter') . ':') !!}
        {!! Form::text('sns_twitter', null, ['class' => 'form-control', 'maxlength' => 255]) !!}
        {{ validationMessage($errors->first('sns_twitter')) }}
    </div>

    <!-- Sns Instagram Field -->
    <div class="form-group {{ validationClass($errors->has('sns_instagram')) }}">
        {!! Form::label('sns_instagram', __('cmsadmin::models/testimonials.fields.sns_instagram') . ':') !!}
        {!! Form::text('sns_instagram', null, ['class' => 'form-control', 'maxlength' => 255]) !!}
        {{ validationMessage($errors->first('sns_instagram')) }}
    </div>

    <!-- Sns Youtube Field -->
    <div class="form-group {{ validationClass($errors->has('sns_youtube')) }}">
        {!! Form::label('sns_youtube', __('cmsadmin::models/testimonials.fields.sns_youtube') . ':') !!}
        {!! Form::text('sns_youtube', null, ['class' => 'form-control', 'maxlength' => 255]) !!}
        {{ validationMessage($errors->first('sns_youtube')) }}
    </div>
</div>

@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            $('[data-toggle="switch"]').bootstrapSwitch('state', $(this).prop('checked'));
        })
    </script>
@endpush

@include('common::__partial.filepond-scripts')
@include('common::__partial.remove_image_js')
@include('common::__partial.remove_tmp_image_js')
