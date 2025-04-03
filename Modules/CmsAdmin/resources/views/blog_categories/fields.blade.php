<div class = "col-sm-6">
    <!-- Cat Title Field -->
    <div class="form-group required {{ validationClass($errors->has('cat_title')) }}">
        {!! Form::label('cat_title', __('cmsadmin::models/blog_categories.fields.cat_title') . ':') !!}
        {!! Form::text('cat_title', null, [
            'class' => 'form-control ' . validationInputClass($errors->has('cat_title')),
            'maxlength' => 191,
        ]) !!}
        {{ validationMessage($errors->first('cat_title')) }}
    </div>

    <!-- Cat Slug Field -->
    <div class="form-group required {{ validationClass($errors->has('cat_slug')) }}">
        {!! Form::label('cat_slug', __('cmsadmin::models/blog_categories.fields.cat_slug') . ':') !!}
        {!! Form::text('cat_slug', null, [
            'class' => 'form-control ' . validationInputClass($errors->has('cat_slug')),
            'placeholder' => __('common::crud.messages.auto_generate_slug'),
            'maxlength' => 191,
        ]) !!}
        {{ validationMessage($errors->first('cat_slug')) }}
    </div>
</div>

<!-- Cat Image Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('cat_image')) }}">
    {!! Form::label('cat_image', __('cmsadmin::models/blog_categories.fields.cat_image') . ':') !!}
    <div class="clearfix"></div>
    {!! Form::hidden('cat_image_pre', null) !!}
    {!! Form::file('cat_image', [
        'id' => 'filepond1',
        'class' => 'my-pond',
        'value' => !empty($blogCategory->cat_image) ? $blogCategory->cat_image : null,
    ]) !!}
    <div class="image-help-text" id="basic-addon4"> {{ __('cmsadmin::models/blog_categories.optimal_image_size') }}</div>
    {{ validationMessage($errors->first('cat_image')) }}
    @if (old('cat_image') && file_exists(storage_path('tmp/' . old('cat_image'))))
        <p class="temp-image m-1">
            {!! Form::hidden('cat_image', old('cat_image'), [
                'id' => 'filepond1-cat_image',
            ]) !!}
            {!! renderTmpImage(old('cat_image'), IMAGE_WIDTH_200) !!}
            {!! renderTmpImageDeleteIcon(old('cat_image')) !!}
        </p>
    @elseif (!empty($blogCategory->cat_image))
        <p class="del-form-image m-1">
            {!! Form::hidden('cat_image', !empty($blogCategory->cat_image) ? $blogCategory->cat_image : null, [
                'id' => 'filepond1-cat_image',
            ]) !!}
        <div class="d-flex align-items-end">
            <div class="mr-1">
                {!! renderFancyboxImage(BLOG_CATEGORY_FILE_DIR_NAME, $blogCategory->cat_image, IMAGE_WIDTH_200) !!}
            </div>
            {!! renderImageDeleteIcon(route('cmsadmin.blogCategories.removeImage', [$blogCategory->cat_id]), 'cat_image') !!}
        </div>
        </p>
    @endif
</div>

@push('page_scripts')
    <script>
        $(function() {
            var isMultiUpload = false,
                moduleName = "blogCategory",
                upload_url = "{{ route('common.imageHandler.fileupload') }}",
                delete_url = "{{ route('common.imageHandler.destroy') }}";
            initializeFilePond("filepond1", "cat_image", moduleName, upload_url, delete_url, isMultiUpload);
        });
    </script>
@endpush

<!-- Remarks Field -->
<div class="form-group col-sm-12 col-lg-12 {{ validationClass($errors->has('remarks')) }}">
    {!! Form::label('remarks', __('cmsadmin::models/blog_categories.fields.remarks') . ':') !!}
    {!! Form::textarea('remarks', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('remarks')),
        'maxlength' => 65535,
    ]) !!}
    {{ validationMessage($errors->first('remarks')) }}
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
