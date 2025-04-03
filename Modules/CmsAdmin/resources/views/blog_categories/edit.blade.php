@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('blog_category_edit', $blogCategory) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        <h1>{{ __('common::crud.edit') }} {{ __('cmsadmin::models/blog_categories.singular') }}</h1>
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-7">
            {!! Form::model($blogCategory, [
                'route' => ['cmsadmin.blogCategories.update', $blogCategory->cat_id],
                'method' => 'patch',
            ]) !!}

            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::blog_categories.fields')
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex">
                    <!-- Publish Field -->
                    <div class="form-group {{ validationClass($errors->has('publish')) }}">
                        <div class="form-check mr-3 pl-0">
                            {!! Form::label('publish', __('common::crud.fields.publish') . ':') !!}
                            @if (checkCmsAdminPermission('blogCategories.togglePublish'))
                                {!! Form::hidden('publish', 2) !!}
                                {!! renderBootstrapSwitchPublish('publish', $id, $publish, old('publish')) !!}
                                {{ validationMessage($errors->first('publish')) }}
                            @else
                                {{ getPublishText(2) }}
                            @endif
                        </div>
                    </div>

                    <!-- Reserved Field -->
                    <div class="form-group {{ validationClass($errors->has('reserved')) }}">
                        <div class="form-check mr-3 pl-0">
                            {!! Form::label('reserved', __('common::crud.fields.reserved') . ':') !!}
                            @if (checkCmsAdminPermission('blogCategories.toggleReserved'))
                                {!! Form::hidden('reserved', 2) !!}
                                {!! renderBootstrapSwitchReserved('reserved', $id, $reserved, old('reserved')) !!}
                                {{ validationMessage($errors->first('reserved')) }}
                            @else
                                {{ getReservedText(2) }}
                            @endif
                        </div>
                    </div>
                </div>
                {!! renderSubmitButton(__('common::crud.update'), 'primary', '') !!}
                {!! renderLinkButton(__('common::crud.cancel'), route('cmsadmin.blogCategories.index'), 'times', 'warning', '') !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
