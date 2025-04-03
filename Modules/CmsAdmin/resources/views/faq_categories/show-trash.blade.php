@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('faqCategory_trash_detail', $faqCategory) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/faq_categories.singular') }} {{ __('common::crud.detail') }} -
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
                    @include('cmsadmin::faq_categories.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkCmsAdminPermission('faqCategories.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.faqCategories.trashList'), 'chevron-circle-left', 'dark', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['faqCategories.create', 'faqCategories.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.faqCategories.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermission('faqCategories.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $faqCategory->faq_cat_id,
                        route('cmsadmin.faqCategories.restore', ['id' => $faqCategory->faq_cat_id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('faqCategories.permanentDestroy'))
                    {!! renderDeleteBtn(
                        'cmsadmin.faqCategories.permanentDestroy',
                        $faqCategory->faq_cat_id,
                        $faqCategory->reserved == 2,
                        null,
                        'trash',
                        'permanent',
                    ) !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.trash_restore')
@include('common::__partial.swal_datatable')
