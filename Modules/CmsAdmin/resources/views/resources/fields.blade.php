<!-- Display Name Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('display_name')) }}">
    {!! Form::label('display_name', __('cmsadmin::models/resources.fields.display_name') . ':') !!}
    {!! Form::text('display_name', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('display_name')),
        'maxlength' => 100,
    ]) !!}
    {{ validationMessage($errors->first('display_name')) }}
</div>

<!-- File Name Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('file_name')) }}">
    {!! Form::label('file_name', __('cmsadmin::models/resources.fields.file_name') . ':') !!}
    <div class="clearfix"></div>
    {!! Form::hidden('file_name_pre', !empty($resource->file_name) ? $resource->file_name : null) !!}
    {!! Form::file('file_name', [
        'id' => 'filepond1',
        'class' => 'my-pond',
    ]) !!}
    @if (old('file_name') && file_exists(storage_path('tmp/' . old('file_name'))))
        {!! Form::hidden('file_name', old('file_name'), [
            'id' => 'filepond1-file_name',
        ]) !!}
        <p class="m-1">{!! renderFileIcon(old('file_name')) !!}</p>
    @elseif (!empty($resource->file_name))
        {!! Form::hidden('file_name', !empty($resource->file_name) ? $resource->file_name : null, [
            'id' => 'filepond1-file_name',
        ]) !!}
        <p class="m-1">{!! renderFileIcon($resource->file_name) !!}</p>
    @endif
    {{ validationMessage($errors->first('file_name')) }}
</div>

<div class="form-group col-sm-12">
    <!-- Publish Field -->
    <div class="form-group {{ validationClass($errors->has('publish')) }}">
        <div class="form-check mr-3 pl-0">
            {!! Form::label('publish', __('common::crud.fields.publish') . ':') !!}
            @if (checkCmsAdminPermission('resources.togglePublish'))
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
            @if (checkCmsAdminPermission('resources.toggleReserved'))
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
    {!! renderLinkButton(__('common::crud.btn.cancel'), route('cmsadmin.resources.index'), 'times', 'warning', '') !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            var isMultiUpload = false,
                moduleName = "Resource",
                upload_url = "{{ route('common.imageHandler.fileupload') }}",
                delete_url = "{{ route('common.imageHandler.destroy') }}";
            initializeFilePond("filepond1", "file_name", moduleName, upload_url, delete_url, isMultiUpload);

            //tooglescript
            $('[data-toggle="switch"]').bootstrapSwitch('state', $(this).prop('checked'));
        });
    </script>
@endpush

@include('common::__partial.filepond-file-scripts')
