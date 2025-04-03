@extends('layouts.modal')

@section('content')
    {!! Form::model($sns, [
        'route' => ['sys.sns.imageUpdate', ['id' => $sns->sns_id, 'field' => $field]],
        'method' => 'patch',
    ]) !!}
    <div class="modal-body">
        <!-- Image Field -->
        <div class="form-group col-sm-8 {{ validationClass($errors->has($field)) }}">
            {!! Form::label($field, __('sys::models/sns.fields.' . $field) . ':') !!}
            <div class="clearfix"></div>
            {!! Form::hidden($field . '_pre', !empty($sns->$field) ? $sns->$field : null) !!}
            {!! Form::file($field, [
                'id' => 'filepond1',
                'class' => 'my-pond',
            ]) !!}
            <div class="image-help-text" id="basic-addon4"> {{ __('sys::models/sns.fields.optimal_image_size') }}</div>
            {{ validationMessage($errors->first($field)) }}
            @if (old($field) && file_exists(storage_path('tmp/' . old($field))))
                <p class="temp-image m-1">
                    {!! Form::hidden($field, old($field), [
                        'id' => 'filepond1-' . $field,
                    ]) !!}
                    {!! renderTmpImage(old($field), IMAGE_WIDTH_100) !!}
                    {!! renderTmpImageDeleteIcon(old($field)) !!}
                </p>
            @elseif (!empty($sns->$field))
                <p class="del-form-image m-1">
                    {!! Form::hidden($field, !empty($sns->$field) ? $sns->$field : null, [
                        'id' => 'filepond1-' . $field,
                    ]) !!}
                    {!! renderImage(SNS_FILE_DIR_NAME, $sns->$field, IMAGE_WIDTH_100) !!}
                    {!! renderImageDeleteIcon(route('sys.sns.removeImage', [$sns->sns_id]), $field) !!}
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
                    moduleName = "sns",
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
