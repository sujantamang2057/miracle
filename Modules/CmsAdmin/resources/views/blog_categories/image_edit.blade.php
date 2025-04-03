<?php
$html = '';
?>
@extends('layouts.modal')

@section('content')
    {!! Form::model($blogCategory, [
        'route' => ['cmsadmin.blogCategories.imageUpdate', ['id' => $blogCategory->cat_id, 'field' => $field]],
        'method' => 'patch',
    ]) !!}
    <div class="modal-body">
        <!-- Image Field -->
        <div class="form-group col-sm-8 {{ validationClass($errors->has($field)) }}">
            {!! Form::label($field, __('cmsadmin::models/blog_categories.fields.' . $field) . ':') !!}
            <div class="clearfix"></div>
            {!! Form::hidden($field . '_pre', !empty($blogCategory->$field) ? $blogCategory->$field : null) !!}
            {!! Form::file($field, [
                'id' => 'filepond1',
                'class' => 'my-pond',
            ]) !!}
            <div class="image-help-text" id="basic-addon4">
                {{ __('cmsadmin::models/blog_categories.optimal_image_size') }}
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
            @elseif (!empty($blogCategory->$field))
                <p class="del-form-image m-1">
                    {!! Form::hidden($field, !empty($blogCategory->$field) ? $blogCategory->$field : null, [
                        'id' => 'filepond1-' . $field,
                    ]) !!}
                    {!! renderImage(BLOG_CATEGORY_FILE_DIR_NAME, $blogCategory->$field, IMAGE_WIDTH_200) !!}
                    {!! renderImageDeleteIcon(route('cmsadmin.blogCategories.removeImage', [$blogCategory->cat_id]), $field) !!}
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
                    moduleName = "blogCategory",
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
