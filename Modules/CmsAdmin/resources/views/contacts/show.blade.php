@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('contact_detail', $contact) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        <h1>{{ __('cmsadmin::models/contacts.singular') }} {{ __('common::crud.detail') }}</h1>
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::contacts.show_fields')
                </div>
                <div class="d-flex column-gap-5 mt-3">
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.contacts.index'), 'chevron-circle-left', 'warning', '') !!}
                    @if (checkCmsAdminPermission('contacts.exportPdf'))
                        {!! renderLinkButton(
                            __('common::crud.export_pdf'),
                            route('cmsadmin.contacts.exportPdf', [$contact->contact_id]),
                            'fa-solid fa-file-export',
                            'primary',
                            '',
                        ) !!}
                    @endif
                    @if ($contact->mail_sent_count < 1 && checkCmsAdminPermissionList(['contacts.resendMail', 'contacts.loadResendMail']))
                        <a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#contactModal"><i class="fas fa-envelope"></i>
                            {{ __('cmsadmin::models/contacts.resend_mail') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if ($contact->mail_sent_count < 1)
        @include('cmsadmin::contacts.modal')
    @endif
@endsection
