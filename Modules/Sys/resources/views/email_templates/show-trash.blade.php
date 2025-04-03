@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('emailTemplate_detail', $emailTemplate) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('sys::models/email_templates.plural') }} {{ __('common::crud.detail') }} -
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
                    @include('sys::email_templates.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkSysPermission('emailTemplates.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('sys.emailTemplates.trashList'), 'chevron-circle-left', 'dark', '') !!}
                @endif
                @if (checkSysPermissionList(['emailTemplates.create', 'emailTemplates.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('sys.emailTemplates.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkSysPermission('emailTemplates.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $emailTemplate->template_id,
                        route('sys.emailTemplates.restore', ['id' => $emailTemplate->template_id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkSysPermission('emailTemplates.permanentDestroy'))
                    {!! renderDeleteBtn(
                        'sys.emailTemplates.permanentDestroy',
                        $emailTemplate->template_id,
                        $emailTemplate->reserved == 2,
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
