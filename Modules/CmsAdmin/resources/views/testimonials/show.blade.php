@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('testimonial_detail', $testimonial) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/testimonials.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::testimonials.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.testimonials.index'), 'chevron-circle-left', 'warning', '') !!}
                @if (checkCmsAdminPermissionList(['testimonials.create', 'testimonials.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.testimonials.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['testimonials.edit', 'testimonials.update']))
                    {!! renderLinkButton(__('common::crud.update'), route('cmsadmin.testimonials.edit', [$testimonial->testimonial_id]), 'edit', 'primary', '') !!}
                @endif
                @if (checkCmsAdminPermission('testimonials.destroy'))
                    {!! renderDeleteBtn('cmsadmin.testimonials.destroy', $testimonial->testimonial_id, $testimonial->reserved == 2) !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.show_alert')
@include('common::__partial.swal_datatable')
@include('common::__partial.publish_toggle')
@include('common::__partial.reserved_toggle')
@include('common::__partial.remove_image_js')
