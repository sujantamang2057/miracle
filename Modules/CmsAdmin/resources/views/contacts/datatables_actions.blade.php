<input type="hidden" name="id" value="{{ $contact_id }}" />
<div class='action-buttons action-col-3'>
    @if (checkCmsAdminPermission('contacts.show'))
        {!! renderActionIcon(route('cmsadmin.contacts.show', $contact_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermission('contacts.exportPdf'))
        {!! renderActionIcon(route('cmsadmin.contacts.exportPdf', $contact_id), 'file-export', __('common::crud.export_pdf'), 'text-primary', 'sm') !!}
    @endif
    @if ($mail_sent_count < 1 && checkCmsAdminPermissionList(['contacts.resendMail', 'contacts.loadResendMail']))
        {!! renderActionIcon(
            route('cmsadmin.contacts.loadResendMail', $contact_id),
            'envelope',
            __('cmsadmin::models/contacts.resend_mail'),
            'text-primary',
            'sm load-resend-mail',
        ) !!}
    @endif
</div>
