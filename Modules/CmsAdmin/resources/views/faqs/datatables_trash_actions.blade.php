{!! Form::open([
    'route' => ['cmsadmin.faqs.permanentDestroy', $faq_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $faq_id,
]) !!}
<div class='action-buttons action-col-2'>
    @if (checkCmsAdminPermission('faqs.show'))
        {!! renderActionIcon(route('cmsadmin.faqs.show', [$faq_id, 'mode=trash-restore']), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermission('faqs.restore'))
        {!! renderActionIcon(
            route('cmsadmin.faqs.restore', ['id' => $faq_id]),
            'recycle text-warning',
            __('common::crud.restore'),
            'text-danger btn-trash-restore',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('faqs.permanentDestroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.permanent_delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$faq_id', 'permanent')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}

