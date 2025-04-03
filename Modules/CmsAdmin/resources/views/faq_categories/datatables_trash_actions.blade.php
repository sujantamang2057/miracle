{!! Form::open([
    'route' => ['cmsadmin.faqCategories.permanentDestroy', $faq_cat_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $faq_cat_id,
]) !!}
<div class='action-buttons action-col-2'>
    @if (checkCmsAdminPermission('faqCategories.show'))
        {!! renderActionIcon(
            route('cmsadmin.faqCategories.show', [$faq_cat_id, 'mode=trash-restore']),
            'eye',
            __('common::crud.view'),
            'text-success',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('faqCategories.restore'))
        {!! renderActionIcon(
            route('cmsadmin.faqCategories.restore', ['id' => $faq_cat_id]),
            'recycle text-warning',
            __('common::crud.restore'),
            'text-danger btn-trash-restore',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('faqCategories.permanentDestroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.permanent_delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$faq_cat_id', 'permanent')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}

