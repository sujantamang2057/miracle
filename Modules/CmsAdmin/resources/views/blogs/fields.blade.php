<div class = "col-sm-6">
    <!-- Title Field -->
    <div class="form-group required {{ validationClass($errors->has('title')) }}">
        {!! Form::label('title', __('cmsadmin::models/blogs.fields.title') . ':') !!}
        {!! Form::text('title', null, ['class' => 'form-control', 'required', 'maxlength' => 191]) !!}
        {{ validationMessage($errors->first('title')) }}

    </div>

    <!-- Slug Field -->
    <div class="form-group required {{ validationClass($errors->has('slug')) }}">
        {!! Form::label('slug', __('cmsadmin::models/blogs.fields.slug') . ':') !!}
        {!! Form::text('slug', null, [
            'class' => 'form-control ' . validationInputClass($errors->has('slug')),
            'placeholder' => __('common::crud.messages.auto_generate_slug'),
            'maxlength' => 191,
        ]) !!}
        {{ validationMessage($errors->first('slug')) }}
    </div>
</div>

<div class = "col-sm-6">
    <!-- Cat Id Field -->
    <div class="form-group required {{ validationClass($errors->has('cat_id')) }}">
        {!! Form::label('cat_id', __('cmsadmin::models/blogs.fields.cat_id') . ':') !!}
        {!! Form::select('cat_id', $blogCatList, null, [
            'class' => 'form-control ' . validationInputClass($errors->has('cat_id')),
        ]) !!}
        {{ validationMessage($errors->first('cat_id')) }}
    </div>
</div>

<!-- Detail Field -->
<div class="form-group col-md-12 required {{ validationClass($errors->has('detail')) }}">
    {!! Form::label('detail', __('cmsadmin::models/blogs.fields.detail') . ':') !!}
    {!! Form::textarea('detail', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('detail')),
        'id' => 'detail',
        'maxlength' => 65535,
    ]) !!} {{ validationMessage($errors->first('detail')) }}
</div>
<x-head.tinymce-config />

<!-- Thumb Image Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('thumb_image')) }}">
    {!! Form::label('thumb_image', __('cmsadmin::models/blogs.fields.thumb_image') . ':') !!}
    <div class="clearfix"></div>
    {!! Form::hidden('thumb_image_pre', null) !!}
    {!! Form::file('thumb_image', [
        'id' => 'filepond1',
        'class' => 'my-pond',
        'value' => !empty($blog->thumb_image) ? $blog->thumb_image : null,
    ]) !!}
    <div class="image-help-text" id="basic-addon4"> {{ __('cmsadmin::models/blogs.optimal_image_size_thumb') }}</div>
    {{ validationMessage($errors->first('thumb_image')) }}
    @if (old('thumb_image') && file_exists(storage_path('tmp/' . old('thumb_image'))))
        <p class="temp-image m-1">
            {!! Form::hidden('thumb_image', old('thumb_image'), [
                'id' => 'filepond1-thumb_image',
            ]) !!}
            {!! renderTmpImage(old('thumb_image'), IMAGE_WIDTH_200) !!}
            {!! renderTmpImageDeleteIcon(old('thumb_image')) !!}
        </p>
    @elseif (!empty($blog->thumb_image))
        <p class="del-form-image m-1">
            {!! Form::hidden('thumb_image', !empty($blog->thumb_image) ? $blog->thumb_image : null, [
                'id' => 'filepond1-thumb_image',
            ]) !!}
        <div class="d-flex align-items-end">
            <div class="mr-1">
                {!! renderFancyboxImage(BLOG_FILE_DIR_NAME, $blog->thumb_image, IMAGE_WIDTH_200) !!}
            </div>
            {!! renderImageDeleteIcon(route('cmsadmin.blogs.removeImage', [$blog->blog_id]), 'thumb_image') !!}
        </div>
        </p>
    @endif
</div>

<!-- Image Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('image')) }}">
    {!! Form::label('image', __('cmsadmin::models/blogs.fields.image') . ':') !!}
    <div class="clearfix"></div>
    {!! Form::hidden('image_pre', null) !!}
    {!! Form::file('image', [
        'id' => 'filepond2',
        'class' => 'my-pond',
        'value' => !empty($blog->image) ? $blog->image : null,
    ]) !!}
    <div class="image-help-text" id="basic-addon4"> {{ __('cmsadmin::models/blogs.optimal_image_size') }}</div>
    {{ validationMessage($errors->first('image')) }}
    @if (old('image') && file_exists(storage_path('tmp/' . old('image'))))
        <p class="temp-image m-1">
            {!! Form::hidden('image', old('image'), [
                'id' => 'filepond1-image',
            ]) !!}
            {!! renderTmpImage(old('image'), IMAGE_WIDTH_200) !!}
            {!! renderTmpImageDeleteIcon(old('image')) !!}
        @elseif (!empty($blog->image))
        <p class="del-form-image m-1">
            {!! Form::hidden('image', !empty($blog->image) ? $blog->image : null, [
                'id' => 'filepond2-image',
            ]) !!}
        <div class="d-flex align-items-end">
            <div class="mr-1">
                {!! renderFancyboxImage(BLOG_FILE_DIR_NAME, $blog->image, IMAGE_WIDTH_200) !!}
            </div>
            {!! renderImageDeleteIcon(route('cmsadmin.blogs.removeImage', [$blog->blog_id]), 'image') !!}
        </div>
        </p>
    @endif
</div>

@push('page_scripts')
    <script>
        $(function() {
            var isMultiUpload = false,
                moduleName = "blog",
                upload_url = "{{ route('common.imageHandler.fileupload') }}",
                delete_url = "{{ route('common.imageHandler.destroy') }}";
            initializeFilePond("filepond1", "thumb_image", moduleName, upload_url, delete_url, isMultiUpload);
            initializeFilePond("filepond2", "image", moduleName, upload_url, delete_url, isMultiUpload);
        });
    </script>
@endpush

<div class = "col-sm-6">
    <!-- Video Url Field -->
    <div class="form-group {{ validationClass($errors->has('video_url')) }}">
        {!! Form::label('video_url', __('cmsadmin::models/blogs.fields.video_url') . ':') !!}
        {!! Form::text('video_url', null, ['class' => 'form-control', 'maxlength' => 255]) !!}
        {{ validationMessage($errors->first('video_url')) }}
    </div>

    <!-- Remarks Field -->
    <div class="form-group {{ validationClass($errors->has('remarks')) }}">
        {!! Form::label('remarks', __('cmsadmin::models/blogs.fields.remarks') . ':') !!}
        {!! Form::textarea('remarks', null, ['class' => 'form-control', 'maxlength' => 65535]) !!}
        {{ validationMessage($errors->first('remarks')) }}
    </div>
</div>

<div class = "col-sm-6">
    <!-- Display Date Field -->
    <div class="form-group required {{ validationClass($errors->has('display_date')) }}">
        {!! Form::label('display_date', __('cmsadmin::models/blogs.fields.display_date') . ':') !!}
        {!! Form::text('display_date', null, ['class' => 'form-control', 'id' => 'display_date']) !!}
        {{ validationMessage($errors->first('display_date')) }}
    </div>

    @push('page_scripts')
        <script type="text/javascript">
            $(function() {
                initializeDateRangePicker('#display_date');
            });
        </script>
    @endpush

    <!-- Publish From Field -->
    <div class="form-group required {{ validationClass($errors->has('publish_from')) }}">
        {!! Form::label('publish_from', __('common::crud.fields.publish_date_from') . ':') !!}
        {!! Form::text('publish_from', null, ['class' => 'form-control', 'id' => 'publish_from']) !!}
        {{ validationMessage($errors->first('publish_from')) }}
    </div>

    @push('page_scripts')
        <script type="text/javascript">
            $(function() {
                initializeDateRangePicker('#publish_from', true);
            });
        </script>
    @endpush

    <!-- Publish To Field -->
    <div class="form-group {{ validationClass($errors->has('publish_to')) }}">
        {!! Form::label('publish_to', __('common::crud.fields.publish_date_to') . ':') !!}
        {!! Form::text('publish_to', null, ['class' => 'form-control', 'id' => 'publish_to']) !!}
        {{ validationMessage($errors->first('publish_to')) }}
    </div>

    @push('page_scripts')
        <script type="text/javascript">
            $(function() {
                initializeDateRangePicker('#publish_to', true);
            });
        </script>
    @endpush
</div>

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

