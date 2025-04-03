{!! Form::open([
    'route' => ['cmsadmin.blogCategories.permanentDestroy', $cat_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $cat_id,
]) !!}
<div class='action-buttons action-col-2'>
    @if (checkCmsAdminPermission('blogCategories.show'))
        {!! renderActionIcon(
            route('cmsadmin.blogCategories.show', [$cat_id, 'mode=trash-restore']),
            'eye',
            __('common::crud.view'),
            'text-success',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('blogCategories.restore'))
        {!! renderActionIcon(
            route('cmsadmin.blogCategories.restore', ['id' => $cat_id]),
            'recycle text-warning',
            __('common::crud.restore'),
            'text-danger btn-trash-restore',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('blogCategories.permanentdestroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.permanent_delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$cat_id', 'permanent')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}

