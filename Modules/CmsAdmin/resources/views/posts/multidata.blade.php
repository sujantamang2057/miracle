@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('post_multidata', $post) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/posts.singular') }} {{ __('common::multidata.name') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-5">
            @php
                $initTinyMceSelectors = '';
                $initFilepondScripts = '';
                $multidataCount = !empty($multidata) ? count($multidata) : 0;
                $lastKey = $multidataCount - 1;
                $imgDir = POST_FILE_DIR_NAME;
                $modelName = 'PostDetail';
            @endphp
            {!! Form::model($post, ['route' => ['cmsadmin.posts.saveMultidata', $post->post_id], 'method' => 'post']) !!}

            @include('cmsadmin::pages.fields_multidata')

            <div class="card-footer">
                {!! renderSubmitButton(__('common::crud.update'), 'primary', '') !!}
                {!! renderLinkButton(__('common::crud.cancel'), route('cmsadmin.posts.index'), 'times', 'warning', '') !!}
                <a href="javascript:void(0);" class="btn btn-success float-right" id="addMore">
                    <i class="fas fa-plus"></i> {{ __('common::crud.add_more') }}
                </a>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
    <x-head.tinymce-config />
    @include('common::__partial.custom-tinymce')
@endsection

@include('common::__partial.filepond-scripts')
@include('common::__partial.multidata')
@push('page_scripts')
    <script type="text/javascript">
        initTinyMce('{{ $initTinyMceSelectors }}');
        var isMultiUpload = false,
            moduleName = "PostDetail",
            upload_url = "{{ route('common.imageHandler.fileupload') }}",
            delete_url = "{{ route('common.imageHandler.destroy') }}";
        $(function() {
            initializeFilePond("filepond0", "image", moduleName, upload_url, delete_url, isMultiUpload, true, 0);
        });
    </script>
@endpush
