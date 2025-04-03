<!-- Name Field -->
<div class="form-group col-md-6 required {{ validationClass($errors->has('name')) }}">
    {!! Form::label('name', __('sys::models/users.fields.name') . ':') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required', 'autocomplete' => 'off']) !!}
    {{ validationMessage($errors->first('name')) }}
</div>

<!-- Email Field -->
<div class="form-group col-md-6 required {{ validationClass($errors->has('email')) }}">
    {!! Form::label('email', __('sys::models/users.fields.email') . ':') !!}
    {!! Form::email('email', null, ['class' => 'form-control', 'required', 'autocomplete' => 'off']) !!}
    {{ validationMessage($errors->first('email')) }}
</div>

<!-- Profile Image Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('profile_image')) }}">
    {!! Form::label('profile_image', __('sys::models/users.fields.profile_image') . ':') !!}
    <div class="clearfix"></div>
    {!! Form::hidden('profile_image_pre', !empty($user->profile_image) ? $user->profile_image : null) !!}
    {!! Form::file('profile_image', [
        'id' => 'filepond1',
        'class' => 'my-pond',
    ]) !!}
    <div class="image-help-text" id="basic-addon4"> {{ __('sys::models/users.text.optimal_image_size') }}</div>
    {{ validationMessage($errors->first('profile_image')) }}
    @if (old('profile_image') && file_exists(storage_path('tmp/' . old('profile_image'))))
        <p class="temp-image m-1">
            {!! Form::hidden('profile_image', old('profile_image'), [
                'id' => 'filepond1-profile_image',
            ]) !!}
            {!! renderTmpImage(old('profile_image'), 200) !!}
            {!! renderTmpImageDeleteIcon(old('profile_image')) !!}
        </p>
    @elseif (!empty($user->profile_image))
        <p class="del-form-image m-1">
            {!! Form::hidden('profile_image', !empty($user->profile_image) ? $user->profile_image : null, [
                'id' => 'filepond1-profile_image',
            ]) !!}
            {!! renderImage(USER_FILE_DIR_NAME, $user->profile_image, 200) !!}
            {!! renderImageDeleteIcon(route('sys.users.removeImage', [$user->id]), 'profile_image') !!}
        </p>
    @endif
</div>

@push('page_scripts')
    <script>
        $(function() {
            var isMultiUpload = false,
                moduleName = "User",
                upload_url = "{{ route('common.imageHandler.fileupload') }}",
                delete_url = "{{ route('common.imageHandler.destroy') }}";
            initializeFilePond("filepond1", "profile_image", moduleName, upload_url, delete_url, isMultiUpload);
        });
    </script>
@endpush

@include('common::__partial.filepond-scripts')
@include('common::__partial.remove_image_js')
@include('common::__partial.remove_tmp_image_js')
