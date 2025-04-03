<!-- Title Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('title')) }}">
    {!! Form::label('title', __('cmsadmin::models/albums.fields.title') . ':') !!}
    {!! Form::text('title', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('title')),
        'required',
        'maxlength' => 255,
    ]) !!}
    {{ validationMessage($errors->first('title')) }}
</div>

<!-- Date Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('date')) }}">
    {!! Form::label('date', __('cmsadmin::models/albums.fields.date') . ':') !!}
    {!! Form::text('date', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('date')),
        'id' => 'date',
    ]) !!}
    {{ validationMessage($errors->first('date')) }}
</div>

<!-- Slug Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('slug')) }}">
    {!! Form::label('slug', __('cmsadmin::models/albums.fields.slug') . ':') !!}
    {!! Form::text('slug', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('slug')),
        'placeholder' => __('common::crud.messages.auto_generate_slug'),
        'maxlength' => 255,
    ]) !!}
    {{ validationMessage($errors->first('slug')) }}
</div>

<!-- Detail Field -->
<div class="form-group col-sm-12 col-lg-12 {{ validationClass($errors->has('detail')) }}">
    {!! Form::label('detail', __('cmsadmin::models/albums.fields.detail') . ':') !!}
    {!! Form::textarea('detail', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('detail')),
        'maxlength' => 65535,
    ]) !!}
    {{ validationMessage($errors->first('detail')) }}
</div>

<!-- Gallery Field -->
<div class="form-group col-sm-12 {{ validationClass($errors->has('image_name')) }}">
    {!! Form::label('image_name[]', __('cmsadmin::models/albums.gallery') . ':') !!}
    <div class="clearfix"></div>
    {!! Form::file('image_name[]', [
        'id' => 'filepond1',
        'class' => 'my-pond',
    ]) !!}
    <div class="image-help-text" id="basic-addon4"> {{ __('cmsadmin::models/albums.optimal_image_size') }}</div>
    {{ validationMessage($errors->first('image_name[]')) }}
    @if (!empty(old('image_name')) || !empty($album->galleries))
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @if (!empty(old('image_name')))
                        @foreach (old('image_name') as $key => $image)
                            @if (!empty($image))
                                <div class="temp-image col-sm-2 img-gallery-list mb-3">
                                    {!! Form::hidden('image_name[]', $image) !!}
                                    {!! renderFancyboxTmpImage($image, IMAGE_WIDTH_200) !!}
                                    {!! renderTmpImageDeleteIcon($image) !!}
                                </div>
                            @endif
                        @endforeach
                    @endif
                    @if (!empty($album->galleries))
                        @foreach ($album->galleries as $key => $gallery)
                            <div class="col-sm-2 img-gallery-list d-flex align-items-end mb-3">
                                <div class="mr-1">
                                    {!! renderFancyboxImage(ALBUM_FILE_DIR_NAME, $gallery->image_name, IMAGE_WIDTH_200) !!}
                                </div>
                                {!! renderImageDeleteIcon(
                                    route('cmsadmin.galleries.removeGalleryImage', ['album' => $album->album_id, 'gallery' => $gallery]),
                                    $gallery->image_name,
                                ) !!}
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            //file upload script
            var isMultiUpload = true,
                moduleName = "Gallery",
                upload_url = "{{ route('common.imageHandler.fileupload') }}",
                delete_url = "{{ route('common.imageHandler.destroy') }}";
            initializeFilePond("filepond1", "image_name", moduleName, upload_url, delete_url, isMultiUpload);

            //tooglescript
            $('[data-toggle="switch"]').bootstrapSwitch('state', $(this).prop('checked'));

            //dattepickerscript
            initializeDateRangePicker('#date', false);
        });
    </script>
@endpush

@include('common::__partial.daterangepicker')
@include('common::__partial.filepond-scripts')
@include('common::__partial.remove_tmp_image_js')
@include('common::__partial.remove_image_js')
