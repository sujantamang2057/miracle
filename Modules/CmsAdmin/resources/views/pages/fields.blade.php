<div class="row">
    <div class="col-sm-6">
        <!-- Page Title Field -->
        <div class="form-group required {{ validationClass($errors->has('page_title')) }}">
            {!! Form::label('page_title', __('cmsadmin::models/pages.fields.page_title') . ':') !!}
            {!! Form::text('page_title', null, [
                'class' => 'form-control ' . validationInputClass($errors->has('page_title')),
                'maxlength' => 255,
            ]) !!}
            {{ validationMessage($errors->first('page_title')) }}
        </div>

        <!-- Slug Field -->
        <div class="form-group {{ validationClass($errors->has('slug')) }}">
            {!! Form::label('slug', __('cmsadmin::models/pages.fields.slug') . ':') !!}
            {!! Form::text('slug', null, [
                'class' => 'form-control ' . validationInputClass($errors->has('slug')),
                'placeholder' => __('common::crud.messages.auto_generate_slug'),
                'maxlength' => 255,
            ]) !!}
            {{ validationMessage($errors->first('slug')) }}
        </div>

        <!-- Page Type Field -->
        <div class="form-group required {{ validationClass($errors->has('page_type')) }}">
            {!! Form::label('page_type', __('cmsadmin::models/pages.fields.page_type') . ':') !!}
            {!! Form::select('page_type', getPageTypeList(), !empty($page->page_type) ? $page->page_type : null, [
                'class' => 'form-control ' . validationInputClass($errors->has('page_type')),
                'disabled' => !empty($page->page_id),
            ]) !!}
            {{ validationMessage($errors->first('page_type')) }}
        </div>
    </div>
    <div class="col-sm-6">
        <!-- Meta Keyword Field -->
        <div class="form-group">
            {!! Form::label('meta_keyword', __('cmsadmin::models/pages.fields.meta_keyword') . ':') !!}
            {!! Form::text('meta_keyword', null, ['class' => 'form-control', 'maxlength' => 255]) !!}
        </div>

        <!-- Meta Description Field -->
        <div class="form-group">
            {!! Form::label('meta_description', __('cmsadmin::models/pages.fields.meta_description') . ':') !!}
            {!! Form::textarea('meta_description', null, ['class' => 'form-control', 'maxlength' => 255, 'rows' => 2]) !!}
        </div>

        <!-- Banner Image Field -->
        <div class="form-group {{ validationClass($errors->has('banner_image')) }}">
            {!! Form::label('banner_image', __('cmsadmin::models/pages.fields.banner_image') . ':') !!}
            <div class="clearfix"></div>
            {!! Form::hidden('banner_image_pre', null) !!}
            {!! Form::file('banner_image', [
                'id' => 'filepond1',
                'class' => 'my-pond',
                'value' => !empty($page->banner_image) ? $page->banner_image : null,
            ]) !!}
            <div class="image-help-text" id="basic-addon4">
                {{ __('cmsadmin::models/pages.optimal_image_size') }}</div>
            {{ validationMessage($errors->first('banner_image')) }}
            @if (old('banner_image') && file_exists(storage_path('tmp/' . old('banner_image'))))
                <p class="temp-image m-1">
                    {!! Form::hidden('banner_image', old('banner_image'), [
                        'id' => 'filepond1-banner_image',
                    ]) !!}
                    {!! renderTmpImage(old('banner_image'), IMAGE_WIDTH_200) !!}
                    {!! renderTmpImageDeleteIcon(old('banner_image')) !!}
                </p>
            @elseif (getActionName() == 'clone' && !empty($page->banner_image_pre) && file_exists(storage_path('tmp/' . $page->banner_image_pre)))
                <p class="temp-image m-1">
                    {!! Form::hidden('banner_image', $page->banner_image_pre, [
                        'id' => 'filepond1-banner_image',
                    ]) !!}
                    {!! renderTmpImage($page->banner_image_pre, IMAGE_WIDTH_200) !!}
                    {!! renderTmpImageDeleteIcon($page->banner_image_pre) !!}
                </p>
            @elseif (!empty($page->banner_image))
                <p class="del-form-image m-1">
                    {!! Form::hidden('banner_image', !empty($page->banner_image) ? $page->banner_image : null, [
                        'id' => 'filepond1-banner_image',
                    ]) !!}
                <div class="d-flex align-items-end">
                    <div class="mr-1">
                        {!! renderFancyboxImage(PAGE_FILE_DIR_NAME, $page->banner_image, IMAGE_WIDTH_200) !!}
                    </div>
                    {!! renderImageDeleteIcon(route('cmsadmin.pages.removeImage', [$page->page_id]), 'banner_image') !!}
                </div>
                </p>
            @endif
        </div>
    </div>

    <!-- Description Field -->
    <div class="form-group col-sm-12 col-lg-12 {{ validationClass($errors->has('description')) }}">
        {!! Form::label('description', __('cmsadmin::models/pages.fields.description') . ':') !!}
        {!! Form::textarea('description', null, [
            'class' => 'form-control ' . validationInputClass($errors->has('description')),
            'id' => 'tinymce_editor',
            'maxlength' => 65535,
        ]) !!}
        {{ validationMessage($errors->first('description')) }}
    </div>
    @unless (!empty($page->page_type) && $page->page_type == 2)
        <x-head.tinymce-config />
    @endunless

    <!-- Published Date Field -->
    <div class="form-group col-sm-6 required {{ validationClass($errors->has('published_date')) }}">
        {!! Form::label('published_date', __('common::crud.fields.published_date') . ':') !!}
        {!! Form::text('published_date', old('published_date'), [
            'class' => 'form-control ' . validationInputClass($errors->has('published_date')),
            'id' => 'published_date',
            'autocomplete' => 'off',
        ]) !!}
        {{ validationMessage($errors->first('published_date')) }}
    </div>
</div>

@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            $('[data-toggle="switch"]').bootstrapSwitch('state', $(this).prop('checked'));
            initializeDateRangePicker('#published_date');
            var isMultiUpload = false,
                moduleName = "Page",
                upload_url = "{{ route('common.imageHandler.fileupload') }}",
                delete_url = "{{ route('common.imageHandler.destroy') }}";
            initializeFilePond("filepond1", "banner_image", moduleName, upload_url, delete_url, isMultiUpload);
        });
    </script>
@endpush
@include('common::__partial.filepond-scripts')
@include('common::__partial.daterangepicker')
@include('common::__partial.remove_image_js')
@include('common::__partial.remove_tmp_image_js')
@include('common::__partial.custom-tinymce')
@include('common::__partial.preview_js')

