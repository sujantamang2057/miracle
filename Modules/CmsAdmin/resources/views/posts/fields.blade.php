<!-- Post Title Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('category_name')) }}">
    {!! Form::label('post_title', __('cmsadmin::models/posts.fields.post_title') . ':') !!}
    {!! Form::text('post_title', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('post_title')),
        'maxlength' => 191,
    ]) !!}
    {{ validationMessage($errors->first('post_title')) }}
</div>

<!-- Slug Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('slug')) }}">
    {!! Form::label('slug', __('cmsadmin::models/posts.fields.slug') . ':') !!}
    {!! Form::text('slug', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('slug')),
        'placeholder' => __('common::crud.messages.auto_generate_slug'),
        'maxlength' => 191,
    ]) !!}
    {{ validationMessage($errors->first('slug')) }}
</div>

<!-- Category Id Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('category_id')) }}">
    {!! (new Modules\Common\app\Components\Helpers\MultiLevelCategoryHelper($postCategory, LEVEL_FOR_POST))->getMultiCategory(
        'parent_category_id',
        __('cmsadmin::models/posts.fields.category_id'),
        true,
    ) !!}
    {{ validationMessage($errors->first('category_id')) }}
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12 {{ validationClass($errors->has('description')) }}">
    {!! Form::label('description', __('cmsadmin::models/posts.fields.description') . ':') !!}
    {!! Form::textarea('description', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('description')),
        'id' => 'tinymce_editor',
        'maxlength' => 65535,
    ]) !!}
    {{ validationMessage($errors->first('description')) }}
</div>
<x-head.tinymce-config />

<!-- Banner Image Field -->
<div class="form-group col-md-6 {{ validationClass($errors->has('banner_image')) }}">
    {!! Form::label('banner_image', __('cmsadmin::models/posts.fields.banner_image') . ':') !!}
    <div class="clearfix"></div>
    {!! Form::hidden('banner_image_pre', null) !!}
    {!! Form::file('banner_image', [
        'id' => 'filepond1',
        'class' => 'my-pond',
        'value' => !empty($post->banner_image) ? $post->banner_image : null,
    ]) !!}
    <div class="image-help-text" id="basic-addon4"> {{ __('cmsadmin::models/posts.optimal_image_size_banner') }}</div>
    {{ validationMessage($errors->first('banner_image')) }}
    @if (old('banner_image') && file_exists(storage_path('tmp/' . old('banner_image'))))
        <p class="temp-image m-1">
            {!! Form::hidden('banner_image', old('banner_image'), [
                'id' => 'filepond1-banner_image',
            ]) !!}
            {!! renderTmpImage(old('banner_image'), IMAGE_WIDTH_200) !!}
            {!! renderTmpImageDeleteIcon(old('banner_image')) !!}
        </p>
    @elseif (!empty($post->banner_image))
        <p class="del-form-image m-1">
            {!! Form::hidden('banner_image', !empty($post->banner_image) ? $post->banner_image : null, [
                'id' => 'filepond1-banner_image',
            ]) !!}
        <div class="d-flex align-items-end">
            <div class="mr-1">
                {!! renderFancyboxImage(POST_FILE_DIR_NAME, $post->banner_image, IMAGE_WIDTH_200) !!}
            </div>
            {!! renderImageDeleteIcon(route('cmsadmin.posts.removeImage', [$post->post_id]), 'banner_image') !!}
        </div>
        </p>
    @endif
</div>

<!-- Feature Image Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('feature_image')) }}">
    {!! Form::label('feature_image', __('cmsadmin::models/posts.fields.feature_image') . ':') !!}
    <div class="clearfix"></div>
    {!! Form::hidden('feature_image_pre', null) !!}
    {!! Form::file('feature_image', [
        'id' => 'filepond1',
        'class' => 'my-pond',
        'value' => !empty($post->feature_image) ? $post->feature_image : null,
    ]) !!}
    <div class="image-help-text" id="basic-addon4"> {{ __('cmsadmin::models/posts.optimal_image_size_feature') }}</div>
    {{ validationMessage($errors->first('feature_image')) }}
    @if (old('feature_image') && file_exists(storage_path('tmp/' . old('feature_image'))))
        <p class="temp-image m-1">
            {!! Form::hidden('feature_image', old('feature_image'), [
                'id' => 'filepond1-feature_image',
            ]) !!}
            {!! renderTmpImage(old('feature_image'), IMAGE_WIDTH_200) !!}
            {!! renderTmpImageDeleteIcon(old('feature_image')) !!}
        </p>
    @elseif (!empty($post->feature_image))
        <p class="del-form-image m-1">
            {!! Form::hidden('feature_image', !empty($post->feature_image) ? $post->feature_image : null, [
                'id' => 'filepond1-feature_image',
            ]) !!}
        <div class="d-flex align-items-end">
            <div class="mr-1">
                {!! renderFancyboxImage(POST_FILE_DIR_NAME, $post->feature_image, IMAGE_WIDTH_200) !!}
            </div>
            {!! renderImageDeleteIcon(route('cmsadmin.posts.removeImage', [$post->post_id]), 'feature_image') !!}
        </div>
        </p>
    @endif
</div>

@push('page_scripts')
    <script>
        $(function() {
            var isMultiUpload = false,
                moduleName = "Post",
                upload_url = "{{ route('common.imageHandler.fileupload') }}",
                delete_url = "{{ route('common.imageHandler.destroy') }}";
            initializeFilePond("filepond1", "banner_image", moduleName, upload_url, delete_url, isMultiUpload);
            initializeFilePond("filepond1", "feature_image", moduleName, upload_url, delete_url, isMultiUpload);
        });
    </script>
@endpush

<!-- Published Date Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('published_date')) }}">
    {!! Form::label('published_date', __('common::crud.fields.published_date') . ':') !!}
    {!! Form::text('published_date', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('published_date')),
        'id' => 'published_date',
        'autocomplete' => 'off',
    ]) !!}
    {{ validationMessage($errors->first('published_date')) }}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            initializeDateRangePicker('#published_date');
        });
    </script>
@endpush

<!-- Publish Date From Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('publish_date_from')) }}">
    {!! Form::label('publish_date_from', __('common::crud.fields.publish_date_from') . ':') !!}
    {!! Form::text('publish_date_from', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('publish_date_from')),
        'autocomplete' => 'off',
        'id' => 'publish_date_from',
    ]) !!}
    {{ validationMessage($errors->first('publish_date_from')) }}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            initializeDateRangePicker('#publish_date_from', true);
        });
    </script>
@endpush

<!-- Publish Date To Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('publish_date_to')) }}">
    {!! Form::label('publish_date_to', __('common::crud.fields.publish_date_to') . ':') !!}
    {!! Form::text('publish_date_to', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('publish_date_to')),
        'autocomplete' => 'off',
        'id' => 'publish_date_to',
    ]) !!}
    {{ validationMessage($errors->first('publish_date_to')) }}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            initializeDateRangePicker('#publish_date_to', true);
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

@include('common::__partial.daterangepicker')
@include('common::__partial.filepond-scripts')
@include('common::__partial.remove_image_js')
@include('common::__partial.remove_tmp_image_js')
@include('common::__partial.custom-tinymce')
@include('common::__partial.preview_js')
