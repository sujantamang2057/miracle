@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('emailTemplate_detail', $emailTemplate) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('sys::models/email_templates.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('sys::email_templates.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                {!! renderLinkButton(__('common::crud.back'), route('sys.emailTemplates.index'), 'chevron-circle-left', 'warning', '') !!}
                @if (checkSysPermissionList(['emailTemplates.create', 'emailTemplates.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('sys.emailTemplates.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkSysPermissionList(['emailTemplates.edit', 'emailTemplates.update']))
                    {!! renderLinkButton(__('common::crud.update'), route('sys.emailTemplates.edit', [$emailTemplate->template_id]), 'edit', 'primary', '') !!}
                @endif
                @if (checkSysPermission('emailTemplates.regenerate'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.regenerate'),
                        $emailTemplate->template_id,
                        route('sys.emailTemplates.regenerate'),
                        'retweet',
                        'info btn-regenerate',
                        '',
                    ) !!}
                @endif
                @if (checkSysPermission('emailTemplates.destroy'))
                    {!! renderDeleteBtn('sys.emailTemplates.destroy', $emailTemplate->template_id, $emailTemplate->reserved == 2) !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.show_alert')
@include('common::__partial.swal_datatable')
@include('common::__partial.regenerate')
@include('common::__partial.publish_toggle')
@include('common::__partial.reserved_toggle')
