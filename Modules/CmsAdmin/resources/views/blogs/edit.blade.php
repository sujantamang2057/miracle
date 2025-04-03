@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('blog_edit', $blog) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('common::crud.edit') }} {{ __('cmsadmin::models/blogs.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-7">
            {!! Form::model($blog, ['route' => ['cmsadmin.blogs.update', $blog->blog_id], 'method' => 'patch', 'id' => 'blog-form']) !!}
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
                {!! renderSubmitButton(__('common::crud.update'), 'primary', '') !!}
                {!! renderLinkButton(__('common::crud.cancel'), route('cmsadmin.blogs.index'), 'times', 'warning', '') !!}
                {!! renderPreviewButton('blog-form', 'cmsadmin.blogs.preview', $blog->blog_id) !!}

            </div>
        </div>

        {!! Form::close() !!}
    </div>
@endsection
