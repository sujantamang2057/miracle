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

@if ($id !== auth()->user()->id)
    <!-- Role Field -->
    <div class="form-group col-md-6 required {{ validationClass($errors->has('role')) }}">
        {!! Form::label('role', __('sys::models/users.fields.role') . ':') !!}
        {!! Form::select('role', [], null, [
            'id' => 'role',
            'class' => 'form-control',
            'data-placeholder' => __('common::crud.text.select_any'),
            'style' => 'width: 100%;',
            'disabled' => !empty($id) && auth()->user()->id == $id,
        ]) !!}
        {{ validationMessage($errors->first('role')) }}
    </div>
@else
    <div class="form-group col-md-6 readonly">
        <div class="bg-light px-2 pt-2">{!! Form::label('role', __('sys::models/users.fields.role') . ': ') !!} <span>{{ $user->role_name }}</span></div>
    </div>
@endif

@if (getActionName() == 'create')
    <!-- password Field -->
    <div class="form-group col-md-6 required {{ validationClass($errors->has('password')) }}">
        {!! Form::label('password', __('sys::models/users.fields.password') . ':') !!}
        {!! Form::password('password', ['class' => 'form-control', 'required']) !!}
        {{ validationMessage($errors->first('password')) }}
    </div>

    <!-- Confirm Password Field -->
    <div class="form-group col-md-6 required {{ validationClass($errors->has('password_confirmation')) }}">
        {!! Form::label('password_confirmation', __('sys::models/users.fields.password_confirm') . ':') !!}
        {!! Form::password('password_confirmation', ['class' => 'form-control', 'required']) !!}
        {{ validationMessage($errors->first('password_confirmation')) }}
    </div>
    <div class="clearfix"></div>
@endif

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
            {!! renderTmpImage(old('profile_image'), IMAGE_WIDTH_200) !!}
            {!! renderTmpImageDeleteIcon(old('profile_image')) !!}
        </p>
    @elseif (!empty($user->profile_image))
        <p class="del-form-image m-1">
            {!! Form::hidden('profile_image', !empty($user->profile_image) ? $user->profile_image : null, [
                'id' => 'filepond1-profile_image',
            ]) !!}
        <div class="d-flex align-items-end">
            <div class="mr-1">
                {!! renderFancyboxImage(USER_FILE_DIR_NAME, $user->profile_image, IMAGE_WIDTH_200, IMAGE_WIDTH_200) !!}
            </div>
            {!! renderImageDeleteIcon(route('sys.users.removeImage', [$user->id]), 'profile_image') !!}
        </div>
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
@include('common::__partial.select2-scripts')
@push('page_scripts')
    <script>
        $(function() {
            var roleList = {!! json_encode($roleList) !!};
            initializeSelect2('#role', roleList, false);
        });
    </script>
@endpush
