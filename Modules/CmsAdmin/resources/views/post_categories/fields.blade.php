<!-- Category Name Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('category_name')) }}">
    {!! Form::label('category_name', __('cmsadmin::models/post_categories.fields.category_name') . ':') !!}
    {!! Form::text('category_name', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('category_name')),
        'maxlength' => 191,
    ]) !!}
    {{ validationMessage($errors->first('category_name')) }}
</div>

<!-- Slug Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('slug')) }}">
    {!! Form::label('slug', __('cmsadmin::models/post_categories.fields.slug') . ':') !!}
    {!! Form::text('slug', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('slug')),
        'placeholder' => __('common::crud.messages.auto_generate_slug'),
        'maxlength' => 191,
    ]) !!}
    {{ validationMessage($errors->first('slug')) }}
</div>

<!-- Parent Category Id Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('parent_category_id')) }}">
    {!! (new Modules\Common\app\Components\Helpers\MultiLevelCategoryHelper($postCategory, LEVEL_FOR_POST_CATEGORY))->getMultiCategory(
        'parent_category_id',
        __('cmsadmin::models/post_categories.fields.parent_category_id'),
        false,
    ) !!}
    {{ validationMessage($errors->first('parent_category_id')) }}
</div>

<!-- Category Image Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('category_image')) }}">
    {!! Form::label('category_image', __('cmsadmin::models/post_categories.fields.category_image') . ':') !!}
    <div class="clearfix"></div>
    {!! Form::hidden('category_image_pre', null) !!}
    {!! Form::file('category_image', [
        'id' => 'filepond1',
        'class' => 'my-pond',
        'value' => !empty($postCategory->category_image) ? $postCategory->category_image : null,
    ]) !!}
    <div class="image-help-text" id="basic-addon4"> {{ __('cmsadmin::models/post_categories.optimal_image_size') }}</div>
    {{ validationMessage($errors->first('category_image')) }}
    @if (old('category_image') && file_exists(storage_path('tmp/' . old('category_image'))))
        <p class="temp-image m-1">
            {!! Form::hidden('category_image', old('category_image'), [
                'id' => 'filepond1-category_image',
            ]) !!}
            {!! renderTmpImage(old('category_image'), IMAGE_WIDTH_200) !!}
            {!! renderTmpImageDeleteIcon(old('category_image')) !!}
        </p>
    @elseif (!empty($postCategory->category_image))
        <p class="del-form-image m-1">
            {!! Form::hidden('category_image', !empty($postCategory->category_image) ? $postCategory->category_image : null, [
                'id' => 'filepond1-category_image',
            ]) !!}
        <div class="d-flex align-items-end">
            <div class="mr-1">
                {!! renderFancyboxImage(POST_CATEGORY_FILE_DIR_NAME, $postCategory->category_image, IMAGE_WIDTH_200) !!}
            </div>
            {!! renderImageDeleteIcon(route('cmsadmin.postCategories.removeImage', [$postCategory->category_id]), 'category_image') !!}
        </div>
        </p>
    @endif
</div>

<!-- Remarks Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('remarks')) }}">
    {!! Form::label('remarks', __('cmsadmin::models/post_categories.fields.remarks') . ':') !!}
    {!! Form::textarea('remarks', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('file_contents')),
        'maxlength' => 255,
    ]) !!}
    {{ validationMessage($errors->first('remarks')) }}
</div>

@push('page_scripts')
    <script>
        $(function() {
            var isMultiUpload = false,
                moduleName = "News",
                upload_url = "{{ route('common.imageHandler.fileupload') }}",
                delete_url = "{{ route('common.imageHandler.destroy') }}";
            initializeFilePond("filepond1", "category_image", moduleName, upload_url, delete_url, isMultiUpload);

            //tooglescript
            $('[data-toggle="switch"]').bootstrapSwitch('state', $(this).prop('checked'));

        });
    </script>
@endpush

@include('common::__partial.filepond-scripts')
@include('common::__partial.remove_image_js')
@include('common::__partial.remove_tmp_image_js')
