@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('news_category_detail', $newsCategory) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/news_categories.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::news_categories.show_fields')
                </div>

                <div class="d-flex column-gap-5 mt-3">
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.newsCategories.index'), 'chevron-circle-left', 'warning', '') !!}
                    @if (checkCmsAdminPermissionList(['newsCategories.create', 'newsCategories.store']))
                        {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.newsCategories.create'), 'plus', 'success', '') !!}
                    @endif
                    @if (checkCmsAdminPermissionList(['newsCategories.edit', 'newsCategories.update']))
                        {!! renderLinkButton(__('common::crud.update'), route('cmsadmin.newsCategories.edit', [$newsCategory->category_id]), 'edit', 'primary', '') !!}
                    @endif
                    @if (checkCmsAdminPermission('newsCategories.destroy'))
                        {!! renderDeleteBtn('cmsadmin.newsCategories.destroy', $newsCategory->category_id, $newsCategory->reserved == 2) !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.show_alert')
@include('common::__partial.swal_datatable')
@include('common::__partial.publish_toggle')
@include('common::__partial.reserved_toggle')
