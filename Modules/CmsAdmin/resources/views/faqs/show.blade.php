@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('faq_detail', $faq) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/faqs.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::faqs.show_fields')
                </div>
                <div class="d-flex column-gap-5 mt-3">
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.faqs.index'), 'chevron-circle-left', 'warning', '') !!}
                    @if (checkCmsAdminPermissionList(['faqs.create', 'faqs.store']))
                        {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.faqs.create'), 'plus', 'success', '') !!}
                    @endif
                    @if (checkCmsAdminPermissionList(['faqs.edit', 'faqs.update']))
                        {!! renderLinkButton(__('common::crud.update'), route('cmsadmin.faqs.edit', [$faq->faq_id]), 'edit', 'primary', '') !!}
                    @endif
                    @if (checkCmsAdminPermission('faqs.destroy'))
                        {!! renderDeleteBtn('cmsadmin.faqs.destroy', $faq->faq_id, $faq->reserved == 2) !!}
                    @endif
                </div>
            </div>
        </div>
    @endsection

    @include('common::__partial.show_alert')
    @include('common::__partial.swal_datatable')
    @include('common::__partial.publish_toggle')
    @include('common::__partial.reserved_toggle')
