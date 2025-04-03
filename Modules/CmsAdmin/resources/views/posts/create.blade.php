@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('post_create') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        {{ __('common::crud.create') }} {{ __('cmsadmin::models/posts.singular') }}
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-7">
            {!! Form::open(['route' => 'cmsadmin.posts.store', 'id' => 'post-form']) !!}
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::posts.fields')
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex">
                    <!-- Publish Field -->
                    <div class="form-group {{ validationClass($errors->has('publish')) }}">
                        <div class="form-check mr-3 pl-0">
                            {!! Form::label('publish', __('common::crud.fields.publish') . ':') !!}
                            @if (checkCmsAdminPermission('posts.togglePublish'))
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
                {!! renderLinkButton(__('common::crud.cancel'), route('cmsadmin.posts.index'), 'times', 'warning', '') !!}
                {!! renderPreviewButton('post-form', 'cmsadmin.posts.preview') !!}

            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
