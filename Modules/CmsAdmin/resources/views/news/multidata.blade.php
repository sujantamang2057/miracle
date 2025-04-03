@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('news_multidata', $news) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/news.news_multidata.singular') }}</h1>
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
                $imgDir = NEWS_FILE_DIR_NAME;
                $modelName = 'NewsDetail';
            @endphp
            {!! Form::model($news, ['route' => ['cmsadmin.news.saveMultidata', $news->news_id], 'method' => 'post']) !!}

            @include('cmsadmin::pages.fields_multidata')

            <div class="card-footer">
                {!! renderSubmitButton(__('common::crud.update'), 'primary', '') !!}
                {!! renderLinkButton(__('common::crud.cancel'), route('cmsadmin.news.index'), 'times', 'warning', '') !!}
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
            moduleName = "NewsDetail",
            upload_url = "{{ route('common.imageHandler.fileupload') }}",
            delete_url = "{{ route('common.imageHandler.destroy') }}";
        $(function() {
            initializeFilePond("filepond0", "image", moduleName, upload_url, delete_url, isMultiUpload, true, 0);
        });
    </script>
@endpush
