@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('blog_trash_detail', $blog) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/blogs.singular') }} {{ __('common::crud.detail') }} -
                        {{ __('common::crud.trashed') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card card-width-lg">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::blogs.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkCmsAdminPermission('blogs.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.blogs.trashList'), 'chevron-circle-left', 'dark', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['blogs.create', 'blogs.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.blogs.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermission('blogs.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $blog->blog_id,
                        route('cmsadmin.blogs.restore', ['id' => $blog->blog_id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('blogs.permanentDestroy'))
                    {!! renderDeleteBtn('cmsadmin.blogs.permanentDestroy', $blog->blog_id, true, null, 'trash', 'permanent') !!}
                @endif

            </div>
        </div>
    </div>
@endsection

@include('common::__partial.trash_restore')
@include('common::__partial.swal_datatable')
