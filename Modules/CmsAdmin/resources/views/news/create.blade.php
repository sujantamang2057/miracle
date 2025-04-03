@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('news_create') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1> {{ __('common::crud.btn.create') }} {{ __('cmsadmin::models/news.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-7">
            {!! Form::open(['route' => 'cmsadmin.news.store', 'id' => 'news-form']) !!}
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::news.fields')
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex">
                    <!-- Publish Field -->
                    <div class="form-group {{ validationClass($errors->has('publish')) }}">
                        <div class="form-check mr-3 pl-0">
                            {!! Form::label('publish', __('common::crud.fields.publish') . ':') !!}
                            @if (checkCmsAdminPermission('news.togglePublish'))
                                {!! Form::hidden('publish', 2) !!}
                                {!! renderBootstrapSwitchPublish('publish', $id, $publish, old('publish')) !!}
                                {{ validationMessage($errors->first('publish')) }}
                            @else
                                {{ getPublishText(2) }}
                            @endif
                        </div>
                    </div>
                </div>
                {!! renderSubmitButton(__('common::crud.btn.create'), 'success', '') !!}
                {!! renderLinkButton(__('common::crud.btn.cancel'), route('cmsadmin.news.index'), 'times', 'warning', '') !!}
                {!! renderPreviewButton('news-form', 'cmsadmin.news.preview') !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
