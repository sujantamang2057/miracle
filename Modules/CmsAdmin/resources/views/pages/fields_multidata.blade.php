<div class="card-body" id="cloneParent">
    @if (!empty($multidata))
        @foreach ($multidata as $index => $data)
            @php
                $initTinyMceSelectors .= 'textarea#tinymce_editor' . $index . ($index < $lastKey ? ', ' : '');
                $initFilepondScripts .=
                    'initializeFilePond("filepond' . $index . '", "image", moduleName, upload_url, delete_url, isMultiUpload, true, ' . $index . ');';
            @endphp
            <div class="cloneBlock {{ $index > 0 ? 'mt-2' : '' }}">
                @if (isset($data['detail_id']))
                    <input type="hidden" name="multidata[{{ $index }}][detail_id]" value="{{ $data['detail_id'] }}">
                @endif
                <div class="row required">
                    <div class="col-sm-12">
                        {!! Form::text('multidata[' . $index . '][title]', isset($data['title']) ? $data['title'] : null, [
                            'class' => 'form-control ' . validationInputClass($errors->has('multidata.' . $index . '.title')),
                            'maxlength' => 255,
                            'placeholder' => __('common::multidata.fields.title'),
                        ]) !!}
                        {{ validationMessage($errors->first('multidata.' . $index . '.title')) }}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-12">
                        {!! Form::hidden('multidata[' . $index . '][image_pre]', isset($data['image_pre']) ? $data['image_pre'] : null) !!}
                        {!! Form::file('multidata[' . $index . '][image]', [
                            'id' => 'filepond' . $index,
                            'class' => 'my-pond',
                        ]) !!}
                        @if (!empty($data['image']))
                            {!! Form::hidden('multidata[' . $index . '][image]', $data['image'], [
                                'id' => 'filepond' . $index . '-image',
                            ]) !!}
                            <p class="m-1">
                                @if ($data['image'] && file_exists(storage_path('tmp/' . $data['image'])))
                                    {!! renderTmpImage($data['image'], IMAGE_WIDTH_200) !!}
                                @else
                                    {!! renderImage($imgDir, $data['image'], IMAGE_WIDTH_200) !!}
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-12">
                        @php
                            $detail = isset($data['detail']) ? $data['detail'] : null;
                            $layout = isset($data['layout']) ? $data['layout'] : 1;
                        @endphp
                        <div>
                            {!! Form::label('layout', __('common::multidata.fields.layout')) !!}
                        </div>
                        {!! Form::hidden('multidata[' . $index . '][layout]', 1) !!}
                        {!! Form::radio('multidata[' . $index . '][layout]', 1, $layout == 1) !!} <img width="100" src="{{ asset('img/multidata/image-left.png') }}" alt="Left" />
                        {!! Form::radio('multidata[' . $index . '][layout]', 2, $layout == 2) !!} <img width="100" src="{{ asset('img/multidata/image-right.png') }}" alt="Right" />
                        {!! Form::radio('multidata[' . $index . '][layout]', 3, $layout == 3) !!} <img width="100" src="{{ asset('img/multidata/image-top.png') }}" alt="Top" />
                        {!! Form::radio('multidata[' . $index . '][layout]', 4, $layout == 4) !!} <img width="100" src="{{ asset('img/multidata/image-bottom.png') }}" alt="Bottom" />
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-12">
                        {!! Form::textarea('multidata[' . $index . '][detail]', $detail, [
                            'class' => 'form-control ' . validationInputClass($errors->has('multidata[' . $index . '][detail]')),
                            'id' => 'tinymce_editor' . $index,
                            'maxlength' => 65535,
                        ]) !!}
                        {{ validationMessage($errors->first('multidata[' . $index . '][detail]')) }}
                    </div>
                </div>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-multidata mb-2 mt-2"><i class="fa fa-trash"></i>
                    {{ __('common::crud.delete') }}</a>
            </div>
        @endforeach
    @else
        <div class="cloneBlock">
            <div class="row required">
                <div class="col-sm-12">
                    {!! Form::text('multidata[0][title]', null, [
                        'class' => 'form-control ' . validationInputClass($errors->has('multidata.0.title')),
                        'maxlength' => 255,
                        'placeholder' => __('common::multidata.fields.title'),
                    ]) !!}
                    {{ validationMessage($errors->first('multidata.0.title')) }}
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-sm-12">
                    {!! Form::hidden('multidata[0][image_pre]', null) !!}
                    {!! Form::file('multidata[0][image]', [
                        'id' => 'filepond0',
                        'class' => 'my-pond',
                    ]) !!}
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-sm-12">
                    <div>
                        {!! Form::label('layout', __('common::multidata.fields.layout')) !!}
                    </div>
                    {!! Form::hidden('multidata[0][layout]', 1) !!}
                    <div class="d-flex">
                        <div class="mr-3">
                            {!! Form::radio('multidata[0][layout]', 1, true) !!} <img width="100" src="{{ asset('img/multidata/image-left.png') }}" alt="Left" />
                        </div>
                        <div class="mr-3">
                            {!! Form::radio('multidata[0][layout]', 2) !!} <img width="100" src="{{ asset('img/multidata/image-right.png') }}" alt="Right" />
                        </div>
                        <div class="mr-3">
                            {!! Form::radio('multidata[0][layout]', 3) !!} <img width="100" src="{{ asset('img/multidata/image-top.png') }}" alt="Top" />
                        </div>
                        <div class="mr-3">
                            {!! Form::radio('multidata[0][layout]', 4) !!} <img width="100" src="{{ asset('img/multidata/image-bottom.png') }}" alt="Bottom" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-sm-12">
                    {!! Form::textarea('multidata[0][detail]', null, [
                        'class' => 'form-control ' . validationInputClass($errors->has('multidata[0][detail]')),
                        'id' => 'tinymce_editor',
                        'maxlength' => 65535,
                    ]) !!}
                    {{ validationMessage($errors->first('multidata[0][detail]')) }}
                </div>
            </div>
        </div>
    @endif
</div>
@push('page_scripts')
    <script type="text/javascript">
        initTinyMce('{{ $initTinyMceSelectors }}');
        var isMultiUpload = false,
            moduleName = "{{ $modelName }}",
            upload_url = "{{ route('common.imageHandler.fileupload') }}",
            delete_url = "{{ route('common.imageHandler.destroy') }}";
        $(function() {
            @if ($multidataCount)
                {!! $initFilepondScripts !!}
            @else
                initializeFilePond("filepond0", "image", moduleName, upload_url, delete_url, isMultiUpload, true,
                    0);
            @endif
        });
    </script>
@endpush
