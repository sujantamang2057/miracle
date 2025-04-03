@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('blog_create') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('common::crud.create') }} {{ __('cmsadmin::models/blogs.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-7">
            {!! Form::open(['route' => 'cmsadmin.blogs.store', 'id' => 'blog-form']) !!}
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::blogs.fields')
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex">
                    <!-- Publish Field -->
                    <div class="form-group {{ validationClass($errors->has('publish')) }}">
                        <div class="form-check mr-3 pl-0">
                            {!! Form::label('publish', __('common::crud.fields.publish') . ':') !!}
                            @if (checkCmsAdminPermission('blogs.togglePublish'))
                                {!! Form::hidden('publish', 2) !!}
                                {!! renderBootstrapSwitchPublish('publish', $id, $publish, old('publish')) !!}
                                {{ validationMessage($errors->first('publish')) }}
                            @else
                                {{ getPublishText(2) }}
                            @endif
                        </div>
                    </div>
                </div>
                {!! renderSubmitButton(__('common::crud.create'), 'success', '') !!}
                {!! renderLinkButton(__('common::crud.cancel'), route('cmsadmin.blogs.index'), 'times', 'warning', '') !!}
                {!! renderPreviewButton('blog-form', 'cmsadmin.blogs.preview') !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
