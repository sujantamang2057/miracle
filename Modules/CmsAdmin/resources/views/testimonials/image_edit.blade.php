<?php
$html = '';
?>
@extends('layouts.modal')

@section('content')
    {!! Form::model($testimonial, [
        'route' => ['cmsadmin.testimonials.imageUpdate', ['id' => $testimonial->testimonial_id, 'field' => $field]],
        'method' => 'patch',
    ]) !!}
    <div class="modal-body">
        <!-- Image Field -->
        <div class="form-group col-sm-8 {{ validationClass($errors->has($field)) }}">
            {!! Form::label($field, __('cmsadmin::models/testimonials.fields.' . $field) . ':') !!}
            <div class="clearfix"></div>
            {!! Form::hidden($field . '_pre', !empty($testimonial->$field) ? $testimonial->$field : null) !!}
            {!! Form::file($field, [
                'id' => 'filepond1',
                'class' => 'my-pond',
            ]) !!}
            <div class="image-help-text" id="basic-addon4">
                {{ __('cmsadmin::models/testimonials.optimal_image_size') }}
            </div>
            {{ validationMessage($errors->first($field)) }}
            @if (old($field) && file_exists(storage_path('tmp/' . old($field))))
                <p class="temp-image m-1">
                    {!! Form::hidden($field, old($field), [
                        'id' => 'filepond1-' . $field,
                    ]) !!}
                    {!! renderTmpImage(old($field), IMAGE_WIDTH_200) !!}
                    {!! renderTmpImageDeleteIcon(old($field)) !!}
                </p>
            @elseif (!empty($testimonial->$field))
                <p class="del-form-image m-1">
                    {!! Form::hidden($field, !empty($testimonial->$field) ? $testimonial->$field : null, [
                        'id' => 'filepond1-' . $field,
                    ]) !!}
                    {!! renderImage(TESTIMONIAL_FILE_DIR_NAME, $testimonial->$field, IMAGE_WIDTH_200) !!}
                    {!! renderImageDeleteIcon(route('cmsadmin.testimonials.removeImage', [$testimonial->testimonial_id]), $field) !!}
                </p>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        {!! renderSubmitButton(__('common::crud.update'), 'primary', '') !!}
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('common::crud.close') }}</button>
    </div>
    {!! Form::close() !!}
    @push('page_scripts')
        <script>
            $(function() {
                var isMultiUpload = false,
                    moduleName = "testimonial",
                    upload_url = "{{ route('common.imageHandler.fileupload') }}",
                    delete_url = "{{ route('common.imageHandler.destroy') }}";
                initializeFilePond("filepond1", "{{ $field }}", moduleName, upload_url, delete_url,
                    isMultiUpload);
            });
        </script>
    @endpush
@endsection
@include('common::__partial.filepond-scripts')
@include('common::__partial.remove_image_js')
@include('common::__partial.remove_tmp_image_js')
