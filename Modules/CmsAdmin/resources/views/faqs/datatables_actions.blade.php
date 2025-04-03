{!! Form::open([
    'route' => ['cmsadmin.faqs.destroy', $faq_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $faq_id,
]) !!}
<input type="hidden" name="id" value="{{ $faq_id }}" />
<div class='action-buttons'>
    @if (checkCmsAdminPermission('faqs.show'))
        {!! renderActionIcon(route('cmsadmin.faqs.show', $faq_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermissionList(['faqs.edit', 'faqs.update']))
        {!! renderActionIcon(route('cmsadmin.faqs.edit', $faq_id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if ($reserved == 2 && checkCmsAdminPermission('faqs.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            ' title' => __('common::crud.delete'),
            'class' => 'btn text-warning btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$faq_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
