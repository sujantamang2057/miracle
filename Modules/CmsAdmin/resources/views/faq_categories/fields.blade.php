<div class="col-sm-6">
    <!-- Faq Cat Name Field -->
    <div class="form-group required {{ validationClass($errors->has('faq_cat_name')) }}">
        {!! Form::label('faq_cat_name', __('cmsadmin::models/faq_categories.fields.faq_cat_name') . ':') !!}
        {!! Form::text('faq_cat_name', null, [
            'class' => 'form-control ' . validationInputClass($errors->has('faq_cat_name')),
            'maxlength' => 191,
        ]) !!}
        {{ validationMessage($errors->first('faq_cat_name')) }}
    </div>

    <!-- Slug Field -->
    <div class="form-group required {{ validationClass($errors->has('slug')) }}">
        {!! Form::label('slug', __('cmsadmin::models/faq_categories.fields.slug') . ':') !!}
        {!! Form::text('slug', null, [
            'class' => 'form-control ' . validationInputClass($errors->has('slug')),
            'placeholder' => __('common::crud.messages.auto_generate_slug'),
            'maxlength' => 191,
        ]) !!}
        {{ validationMessage($errors->first('slug')) }}
    </div>

    <!-- Remarks Field -->
    <div class="form-group {{ validationClass($errors->has('remarks')) }}">
        {!! Form::label('remarks', __('cmsadmin::models/faq_categories.fields.remarks') . ':') !!}
        {!! Form::textarea('remarks', null, [
            'class' => 'form-control ' . validationInputClass($errors->has('remar')),
            'maxlength' => 255,
        ]) !!}
        {{ validationMessage($errors->first('remarks')) }}
    </div>
</div>

<!-- Faq Cat Image Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('faq_cat_image')) }}">
    {!! Form::label('faq_cat_image', __('cmsadmin::models/faq_categories.fields.faq_cat_image') . ':') !!}
    <div class="clearfix"></div>
    {!! Form::hidden('faq_cat_image_pre', null) !!}
    {!! Form::file('faq_cat_image', [
        'id' => 'filepond1',
        'class' => 'my-pond',
        'value' => !empty($faqCategory->faq_cat_image) ? $faqCategory->faq_cat_image : null,
    ]) !!}
    <div class="image-help-text" id="basic-addon4"> {{ __('cmsadmin::models/faq_categories.optimal_image_size') }}</div>
    {{ validationMessage($errors->first('faq_cat_image')) }}
    @if (old('faq_cat_image') && file_exists(storage_path('tmp/' . old('faq_cat_image'))))
        <p class="temp-image m-1">
            {!! Form::hidden('faq_cat_image', old('faq_cat_image'), [
                'id' => 'filepond1-faq_cat_image',
            ]) !!}
            {!! renderTmpImage(old('faq_cat_image'), IMAGE_WIDTH_200) !!}
            {!! renderTmpImageDeleteIcon(old('faq_cat_image')) !!}
        </p>
    @elseif (!empty($faqCategory->faq_cat_image))
        <p class="del-form-image m-1">
            {!! Form::hidden('faq_cat_image', !empty($faqCategory->faq_cat_image) ? $faqCategory->faq_cat_image : null, [
                'id' => 'filepond1-faq_cat_image',
            ]) !!}
        <div class="d-flex align-items-end">
            <div class="mr-1">
                {!! renderFancyboxImage(FAQ_CATEGORY_FILE_DIR_NAME, $faqCategory->faq_cat_image, IMAGE_WIDTH_200) !!}
            </div>
            {!! renderImageDeleteIcon(route('cmsadmin.faqCategories.removeImage', [$faqCategory->faq_cat_id]), 'faq_cat_image') !!}
        </div>
        </p>
    @endif
</div>

@push('page_scripts')
    <script>
        $(function() {
            //image upload
            var isMultiUpload = false,
                moduleName = "FaqCategory",
                upload_url = "{{ route('common.imageHandler.fileupload') }}",
                delete_url = "{{ route('common.imageHandler.destroy') }}";
            initializeFilePond("filepond1", "faq_cat_image", moduleName, upload_url, delete_url, isMultiUpload);

            //switch btn
            $('[data-toggle="switch"]').bootstrapSwitch('state', $(this).prop('checked'));
        });
    </script>
@endpush

@include('common::__partial.filepond-scripts')
@include('common::__partial.remove_image_js')
@include('common::__partial.remove_tmp_image_js')
