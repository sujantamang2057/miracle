@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('faq_trash_detail', $faq) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/faqs.singular') }} {{ __('common::crud.detail') }} -
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
                    @include('cmsadmin::faqs.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkCmsAdminPermission('faqs.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.faqs.trashList'), 'chevron-circle-left', 'dark', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['faqs.create', 'faqs.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.faqs.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermission('faqs.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $faq->faq_id,
                        route('cmsadmin.faqs.restore', ['id' => $faq->faq_id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('faqs.permanentDestroy'))
                    {!! renderDeleteBtn('cmsadmin.faqs.permanentDestroy', $faq->faq_id, $faq->reserved == 2, null, 'trash', 'permanent') !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.trash_restore')
@include('common::__partial.swal_datatable')
