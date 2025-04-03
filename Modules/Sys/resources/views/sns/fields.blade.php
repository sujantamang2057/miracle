<div class="col-sm-6">
    <!-- Title Field -->
    <div class="form-group required {{ validationClass($errors->has('title')) }}">
        {!! Form::label('title', __('sys::models/sns.fields.title') . ':') !!}
        {!! Form::text('title', null, [
            'class' => 'form-control',
            'required',
            'maxlength' => 100,
            'autocomplete' => 'off',
        ]) !!}
        {{ validationMessage($errors->first('title')) }}
    </div>

    <!-- Icon Field -->
    <div class="form-group {{ validationClass($errors->has('icon')) }}">
        {!! Form::label('icon', __('sys::models/sns.fields.icon') . ':') !!}
        <div class="clearfix"></div>
        {!! Form::hidden('icon_pre', null) !!}
        {!! Form::file('icon', [
            'id' => 'filepond1',
            'class' => 'my-pond',
            'value' => !empty($sns->icon) ? $sns->icon : null,
        ]) !!}
        <div class="image-help-text" id="basic-addon4"> {{ __('sys::models/sns.fields.optimal_image_size') }}</div>
        {{ validationMessage($errors->first('icon')) }}
        @if (old('icon') && file_exists(storage_path('tmp/' . old('icon'))))
            <p class="temp-image m-1">
                {!! Form::hidden('icon', old('icon'), [
                    'id' => 'filepond1-icon',
                ]) !!}
                {!! renderTmpImage(old('icon'), IMAGE_WIDTH_200) !!}
                {!! renderTmpImageDeleteIcon(old('icon')) !!}
            </p>
        @elseif (!empty($sns->icon))
            <p class="del-form-image m-1">
                {!! Form::hidden('icon', !empty($sns->icon) ? $sns->icon : null, [
                    'id' => 'filepond1-icon',
                ]) !!}
            <div class="d-flex align-items-end">
                <div class="mr-1">
                    {!! renderFancyboxImage(SNS_FILE_DIR_NAME, $sns->icon, IMAGE_WIDTH_100) !!}
                </div>
                {!! renderImageDeleteIcon(route('sys.sns.removeImage', [$sns->sns_id]), 'icon') !!}
            </div>
            </p>
        @endif
    </div>

    @push('page_scripts')
        <script>
            $(function() {
                var isMultiUpload = false,
                    maxImageSize = '1MB',
                    minImageSize = '1KB',
                    moduleName = "sns",
                    upload_url = "{{ route('common.imageHandler.fileupload') }}",
                    delete_url = "{{ route('common.imageHandler.destroy') }}";
                initializeFilePond("filepond1", "icon", moduleName, upload_url, delete_url, isMultiUpload, false, 0,
                    maxImageSize, minImageSize);
            });
        </script>
    @endpush
</div>

<div class="col-sm-6">
    <!-- Url Field -->
    <div class="form-group required {{ validationClass($errors->has('url')) }}">
        {!! Form::label('url', __('sys::models/sns.fields.url') . ':') !!}
        {!! Form::text('url', null, [
            'class' => 'form-control',
            'maxlength' => 255,
            'autocomplete' => 'off',
        ]) !!}
        {{ validationMessage($errors->first('url')) }}
    </div>

    <!-- Class Field -->
    <div class="form-group {{ validationClass($errors->has('class')) }}">
        {!! Form::label('class', __('sys::models/sns.fields.class') . ':') !!}
        {!! Form::text('class', null, [
            'class' => 'form-control',
            'maxlength' => 50,
            'autocomplete' => 'off',
        ]) !!}
        {{ validationMessage($errors->first('class')) }}
    </div>
</div>

<div class="form-group col-sm-12">
    <!-- Publish Field -->
    <div class="form-group {{ validationClass($errors->has('publish')) }}">
        <div class="form-check mr-3 pl-0">
            {!! Form::label('publish', __('common::crud.fields.publish') . ':') !!}
            @if (checkSysPermission('sns.togglePublish'))
                {!! Form::hidden('publish', 2) !!}
                {!! renderBootstrapSwitchPublish('publish', $id, $publish, old('publish')) !!}
                {{ validationMessage($errors->first('publish')) }}
            @else
                {{ getPublishText(2) }}
            @endif
        </div>
    </div>

    <!-- Reserved Field -->
    <div class="form-group {{ validationClass($errors->has('reserved')) }}">
        <div class="form-check mr-3 pl-0">
            {!! Form::label('reserved', __('common::crud.fields.reserved') . ':') !!}
            @if (checkSysPermission('sns.toggleReserved'))
                {!! Form::hidden('reserved', 2) !!}
                {!! renderBootstrapSwitchReserved('reserved', $id, $reserved, old('reserved')) !!}
                {{ validationMessage($errors->first('reserved')) }}
            @else
                {{ getReservedText(2) }}
            @endif
        </div>
    </div>
    @if (getActionName() == 'edit')
        {!! renderSubmitButton(__('common::crud.btn.update'), 'primary', '') !!}
    @else
        {!! renderSubmitButton(__('common::crud.btn.create'), 'success', '') !!}
    @endif
    {!! renderLinkButton(__('common::crud.btn.cancel'), route('sys.sns.index'), 'times', 'warning', '') !!}
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
