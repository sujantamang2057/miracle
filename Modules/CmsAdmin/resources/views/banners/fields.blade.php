<!-- Title Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('title')) }}">
    {!! Form::label('title', __('cmsadmin::models/banners.fields.title') . ':') !!}
    {!! Form::text('title', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('title')),
        'maxlength' => 191,
    ]) !!}
    {{ validationMessage($errors->first('title')) }}
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12 {{ validationClass($errors->has('description')) }}">
    {!! Form::label('description', __('cmsadmin::models/banners.fields.description') . ':') !!}
    {!! Form::textarea('description', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('description')),
        'id' => 'tinymce_editor',
        'maxlength' => 300,
    ]) !!}
    {{ validationMessage($errors->first('description')) }}
</div>
<x-head.tinymce-config />

<!-- PC Image Field -->
<div class="form-group col-md-6 required {{ validationClass($errors->has('pc_image')) }}">
    {!! Form::label('pc_image', __('cmsadmin::models/banners.fields.pc_image') . ':') !!}
    <div class="clearfix"></div>
    {!! Form::hidden('pc_image_pre', !empty($banner->pc_image) ? $banner->pc_image : null) !!}
    {!! Form::file('pc_image', [
        'id' => 'filepond1',
        'class' => 'my-pond',
    ]) !!}
    <div class="image-help-text" id="basic-addon4"> {{ __('cmsadmin::models/banners.optimal_image_size_pc') }}</div>
    {{ validationMessage($errors->first('pc_image')) }}
    @if (old('pc_image') && file_exists(storage_path('tmp/' . old('pc_image'))))
        {!! Form::hidden('pc_image', old('pc_image'), [
            'id' => 'filepond1-pc_image',
        ]) !!}
        <p class="m-1">{!! renderTmpImage(old('pc_image'), IMAGE_WIDTH_200) !!}</p>
    @elseif (!empty($banner->pc_image))
        {!! Form::hidden('pc_image', !empty($banner->pc_image) ? $banner->pc_image : null, [
            'id' => 'filepond1-pc_image',
        ]) !!}
        <p class="m-1">
        <div class="d-flex align-items-end">
            <div class="mr-1">
                {!! renderFancyboxImage(BANNER_FILE_DIR_NAME, $banner->pc_image, IMAGE_WIDTH_200) !!}
            </div>
        </div>
        </p>
    @endif
</div>

<!-- SP Image Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('sp_image')) }}">
    {!! Form::label('sp_image', __('cmsadmin::models/banners.fields.sp_image') . ':') !!}
    <div class="clearfix"></div>
    {!! Form::hidden('sp_image_pre', !empty($banner->sp_image) ? $banner->sp_image : null) !!}
    {!! Form::file('sp_image', [
        'id' => 'filepond1',
        'class' => 'my-pond',
    ]) !!}
    <div class="image-help-text" id="basic-addon4"> {{ __('cmsadmin::models/banners.optimal_image_size_sp') }}</div>
    {{ validationMessage($errors->first('sp_image')) }}
    @if (old('sp_image') && file_exists(storage_path('tmp/' . old('sp_image'))))
        <p class="temp-image m-1">
            {!! Form::hidden('sp_image', old('sp_image'), [
                'id' => 'filepond1-sp_image',
            ]) !!}
            {!! renderTmpImage(old('sp_image'), IMAGE_WIDTH_200) !!}
            {!! renderTmpImageDeleteIcon(old('sp_image')) !!}
        </p>
    @elseif (!empty($banner->sp_image))
        <p class="del-form-image m-1">
            {!! Form::hidden('sp_image', !empty($banner->sp_image) ? $banner->sp_image : null, [
                'id' => 'filepond1-sp_image',
            ]) !!}
        <div class="d-flex align-items-end">
            <div class="mr-1">
                {!! renderFancyboxImage(BANNER_FILE_DIR_NAME, $banner->sp_image, IMAGE_WIDTH_200) !!}
            </div>
            {!! renderImageDeleteIcon(route('cmsadmin.banners.removeImage', [$banner->banner_id]), 'sp_image') !!}
        </div>
        </p>
    @endif
</div>

@push('page_scripts')
    <script>
        $(function() {
            var isMultiUpload = false,
                moduleName = "Banner",
                upload_url = "{{ route('common.imageHandler.fileupload') }}",
                delete_url = "{{ route('common.imageHandler.destroy') }}";
            initializeFilePond("filepond1", "pc_image", moduleName, upload_url, delete_url, isMultiUpload);
            initializeFilePond("filepond1", "sp_image", moduleName, upload_url, delete_url, isMultiUpload);
        });
    </script>
@endpush

<!-- Url Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('url')) }}">
    {!! Form::label('url', __('cmsadmin::models/banners.fields.url') . ':') !!}
    {!! Form::text('url', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('url')),
        'maxlength' => 255,
    ]) !!}
    {{ validationMessage($errors->first('url')) }}
</div>

<!-- Url Target Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('url_target')) }}">
    <div class="form-check">
        {!! Form::label('url_target', __('cmsadmin::models/banners.fields.url_target') . ':') !!}
        {!! Form::hidden('url_target', 2) !!}
        {!! renderBootstrapSwitchUrlTarget('url_target', $id, $url_target, old('url_target')) !!}
        {{ validationMessage($errors->first('url_target')) }}
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
@include('common::__partial.custom-tinymce')
