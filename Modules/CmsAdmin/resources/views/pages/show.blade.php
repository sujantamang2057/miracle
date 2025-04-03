@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('page_detail', $page) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/pages.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::pages.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.pages.index'), 'chevron-circle-left', 'warning', '') !!}
                @if (checkCmsAdminPermissionList(['pages.create', 'pages.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.pages.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['pages.edit', 'pages.update']))
                    {!! renderLinkButton(__('common::crud.update'), route('cmsadmin.pages.edit', [$page->page_id]), 'edit', 'primary', '') !!}
                @endif
                @if ($page->page_type == 2 && checkCmsAdminPermission('pages.regenerate'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.regenerate'),
                        $page->page_id,
                        route('cmsadmin.pages.regenerate'),
                        'retweet',
                        'warning btn-regenerate',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('pages.multidata'))
                    {!! renderLinkButton(__('common::multidata.name'), route('cmsadmin.pages.multidata', [$page->page_id]), 'clone', 'secondary', '') !!}
                @endif
                @if (checkCmsAdminPermission('pages.clone'))
                    <div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="far fa-copy"></i>
                            {{ __('common::crud.clone') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-dropup">
                            <a class="dropdown-item" href="{{ route('cmsadmin.pages.clone', $page->page_id) }}">{{ __('common::crud.clone_data') }}</a>
                            <a class="dropdown-item"
                                href="{{ route('cmsadmin.pages.clone', ['id' => $page->page_id, 'mode' => 'image']) }}">{{ __('common::crud.clone_data_n_image') }}</a>
                        </div>
                    </div>
                @endif
                @if (checkCmsAdminPermission('pages.destroy'))
                    {!! renderDeleteBtn('cmsadmin.pages.destroy', $page->page_id, $page->reserved == 2) !!}
                @endif
            </div>
        </div>
    </div>
@endsection
@include('common::__partial.show_alert')
@include('common::__partial.regenerate')
@include('common::__partial.swal_datatable')
@include('common::__partial.publish_toggle')
@include('common::__partial.reserved_toggle')
@include('common::__partial.remove_image_js')
