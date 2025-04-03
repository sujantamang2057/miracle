{!! Form::open([
    'route' => ['cmsadmin.postCategories.permanentDestroy', $category_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $category_id,
]) !!}
<div class='action-buttons action-col-2'>
    @if (checkCmsAdminPermission('postCategories.show'))
        {!! renderActionIcon(
            route('cmsadmin.postCategories.show', [$category_id, 'mode=trash-restore']),
            'eye',
            __('common::crud.view'),
            'text-success',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('postCategories.restore'))
        {!! renderActionIcon(
            route('cmsadmin.postCategories.restore', ['id' => $category_id]),
            'recycle text-warning',
            __('common::crud.restore'),
            'text-danger btn-trash-restore',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('postCategories.permanentDestroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.permanent_delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$category_id', 'permanent')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}

