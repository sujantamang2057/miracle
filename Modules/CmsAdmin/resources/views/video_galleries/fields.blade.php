<!-- Caption Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('caption')) }}">
    {!! Form::label('caption', __('cmsadmin::models/video_galleries.fields.caption') . ':') !!}
    {!! Form::text('caption', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('caption')),
        'maxlength' => 255,
    ]) !!}
    {{ validationMessage($errors->first('caption')) }}
</div>
<div class="col-sm-6"></div>

<!-- Video Url Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('video_url')) }}">
    {!! Form::label('video_url', __('cmsadmin::models/video_galleries.fields.video_url') . ':') !!}
    {!! Form::text('video_url', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('video_url')),
        'maxlength' => 191,
    ]) !!}
    {{ validationMessage($errors->first('video_url')) }}
</div>
<div class="col-sm-6"></div>

<!-- Details Field -->
<div class="form-group col-sm-12 col-lg-12 {{ validationClass($errors->has('details')) }}">
    {!! Form::label('details', __('cmsadmin::models/video_galleries.fields.details') . ':') !!}
    {!! Form::textarea('details', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('details')),
        'id' => 'tinymce_editor',
        'maxlength' => 65535,
    ]) !!}
    {{ validationMessage($errors->first('details')) }}
</div>

<!-- Feature Image Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('feature_image')) }}">
    {!! Form::label('feature_image', __('cmsadmin::models/video_galleries.fields.feature_image') . ':') !!}
    {!! Form::hidden('feature_image_pre', !empty($videoGallery->feature_image) ? $videoGallery->feature_image : null) !!}
    {!! Form::file('feature_image', [
        'id' => 'filepond',
        'class' => 'my-pond',
    ]) !!}
    <div class="image-help-text" id="basic-addon4"> {{ __('cmsadmin::models/video_galleries.optimal_image_size') }}</div>
    {{ validationMessage($errors->first('feature_image')) }}
    @if (old('feature_image') && file_exists(storage_path('tmp/' . old('feature_image'))))
        <p class="temp-image m-1">
            {!! Form::hidden('feature_image', old('feature_image'), [
                'id' => 'filepond1-feature_image',
            ]) !!}
            {!! renderTmpImage(old('feature_image'), IMAGE_WIDTH_200) !!}
            {!! renderTmpImageDeleteIcon(old('feature_image')) !!}
        </p>
    @elseif (!empty($videoGallery->feature_image))
        <p class="del-form-image m-1">
            {!! Form::hidden('feature_image', $videoGallery->feature_image, [
                'id' => 'filepond-feature_image',
            ]) !!}
        <div class="d-flex align-items-end">
            <div class="mr-1">
                {!! renderFancyboxImage(VIDEO_ALBUM_FILE_DIR_NAME, $videoGallery->feature_image, IMAGE_WIDTH_200) !!}
            </div>
            {!! renderImageDeleteIcon(route('cmsadmin.videoGalleries.removeImage', [$videoGallery->video_id]), 'feature_image') !!}
        </div>
        </p>
    @endif
</div>
<div class="col-sm-6"></div>

<!-- Published Date Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('published_date')) }}">
    {!! Form::label('published_date', __('common::crud.fields.published_date') . ':') !!}
    {!! Form::text('published_date', null, [
        'id' => 'published_date',
        'class' => 'form-control ' . validationInputClass($errors->has('published_date')),
    ]) !!}
    {{ validationMessage($errors->first('published_date')) }}
</div>
<div class="col-sm-6"></div>

@push('page_scripts')
    <script type="text/javascript">
        var isMultiUpload = false,
            moduleName = "VideoGallery",
            upload_url = "{{ route('common.imageHandler.fileupload') }}",
            delete_url = "{{ route('common.imageHandler.destroy') }}";
        $(function() {
            initializeDateRangePicker('#published_date');
            $('[data-toggle="switch"]').bootstrapSwitch('state', $(this).prop('checked'));
            initializeFilePond("filepond", "feature_image", moduleName, upload_url, delete_url, isMultiUpload);
        });
    </script>
@endpush
@include('common::__partial.filepond-scripts')
@include('common::__partial.daterangepicker')
@include('common::__partial.remove_image_js')
@include('common::__partial.remove_tmp_image_js')
