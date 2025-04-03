@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('post_trash_detail', $post) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/posts.singular') }} {{ __('common::crud.detail') }} -
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
                    @include('cmsadmin::posts.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkCmsAdminPermission('posts.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.posts.trashList'), 'chevron-circle-left', 'dark', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['posts.create', 'posts.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.posts.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermission('posts.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $post->post_id,
                        route('cmsadmin.posts.restore', ['id' => $post->post_id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('posts.permanentDestroy'))
                    {!! renderDeleteBtn('cmsadmin.posts.permanentDestroy', $post->post_id, true, null, 'trash', 'permanent') !!}
                @endif

            </div>
        </div>
    </div>
@endsection

@include('common::__partial.trash_restore')
@include('common::__partial.swal_datatable')
