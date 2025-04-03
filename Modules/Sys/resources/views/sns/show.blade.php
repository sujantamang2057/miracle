@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('sns_detail', $sns) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('sys::models/sns.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('sys::sns.show_fields')
                </div>
                <div class="d-flex column-gap-5 mt-3">
                    {!! renderLinkButton(__('common::crud.back'), route('sys.sns.index'), 'chevron-circle-left', 'warning', '') !!}
                    @if (checkSysPermissionList(['sns.create', 'sns.store']))
                        {!! renderLinkButton(__('common::crud.create'), route('sys.sns.create'), 'plus', 'success', '') !!}
                    @endif
                    @if (checkSysPermissionList(['sns.edit', 'sns.update']))
                        {!! renderLinkButton(__('common::crud.update'), route('sys.sns.edit', [$sns->sns_id]), 'edit', 'primary', '') !!}
                    @endif
                    @if (checkSysPermission('sns.destroy'))
                        {!! renderDeleteBtn('sys.sns.destroy', $sns->sns_id, $sns->reserved == 2) !!}
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
@include('common::__partial.remove_image_js')
