@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('post_detail', $post) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/posts.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::posts.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.posts.index'), 'chevron-circle-left', 'warning', '') !!}
                @if (checkCmsAdminPermissionList(['posts.create', 'posts.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.posts.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['posts.edit', 'posts.update']))
                    {!! renderLinkButton(__('common::crud.update'), route('cmsadmin.posts.edit', [$post->post_id]), 'edit', 'primary', '') !!}
                @endif
                @if (checkCmsAdminPermission('posts.multidata'))
                    {!! renderLinkButton(__('common::multidata.name'), route('cmsadmin.posts.multidata', [$post->post_id]), 'clone', 'secondary', '') !!}
                @endif
                @if (checkCmsAdminPermission('posts.destroy'))
                    {!! renderDeleteBtn('cmsadmin.posts.destroy', $post->post_id) !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.show_alert')
@include('common::__partial.swal_datatable')
@include('common::__partial.publish_toggle')
@include('common::__partial.remove_image_js')
