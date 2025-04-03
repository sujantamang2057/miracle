@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('news_detail', $news) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/news.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::news.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.news.index'), 'chevron-circle-left', 'warning', '') !!}
                @if (checkCmsAdminPermissionList(['news.create', 'news.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.news.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['news.edit', 'news.update']))
                    {!! renderLinkButton(__('common::crud.update'), route('cmsadmin.news.edit', [$news->news_id]), 'edit', 'primary', '') !!}
                @endif
                @if (checkCmsAdminPermission('news.multidata'))
                    {!! renderLinkButton(__('common::multidata.name'), route('cmsadmin.news.multidata', [$news->news_id]), 'clone', 'secondary', '') !!}
                @endif
                @if (checkCmsAdminPermission('news.destroy'))
                    {!! renderDeleteBtn('cmsadmin.news.destroy', $news->news_id) !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.show_alert')
@include('common::__partial.swal_datatable')
@include('common::__partial.publish_toggle')
@include('common::__partial.remove_image_js')
