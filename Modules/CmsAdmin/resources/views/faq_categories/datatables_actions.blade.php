{!! Form::open([
    'route' => ['cmsadmin.faqCategories.destroy', $faq_cat_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $faq_cat_id,
]) !!}
<input type="hidden" name="id" value="{{ $faq_cat_id }}" />
<div class='action-buttons'>
    @if (checkCmsAdminPermission('faqCategories.show'))
        {!! renderActionIcon(route('cmsadmin.faqCategories.show', $faq_cat_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermissionList(['faqCategories.edit', 'faqCategories.update']))
        {!! renderActionIcon(route('cmsadmin.faqCategories.edit', $faq_cat_id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if ($reserved == 2 && checkCmsAdminPermission('faqCategories.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            ' title' => __('common::crud.delete'),
            'class' => 'btn text-warning btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$faq_cat_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
