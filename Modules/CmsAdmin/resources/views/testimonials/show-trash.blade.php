@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('testimonial_trash_detail', $testimonial) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/testimonials.singular') }} {{ __('common::crud.detail') }} -
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
                    @include('cmsadmin::testimonials.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkCmsAdminPermission('testimonials.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.testimonials.trashList'), 'chevron-circle-left', 'dark', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['testimonials.create', 'testimonials.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.testimonials.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermission('testimonials.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $testimonial->testimonial_id,
                        route('cmsadmin.testimonials.restore', ['id' => $testimonial->testimonial_id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('testimonials.permanentDestroy'))
                    {!! renderDeleteBtn(
                        'cmsadmin.testimonials.permanentDestroy',
                        $testimonial->testimonial_id,
                        $testimonial->reserved == 2,
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
