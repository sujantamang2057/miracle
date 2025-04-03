{!! Form::open([
    'route' => ['cmsadmin.menus.permanentDestroy', $menu_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $menu_id,
]) !!}
<div class='action-buttons action-col-2'>
    @if (checkCmsAdminPermission('menus.show'))
        {!! renderActionIcon(route('cmsadmin.menus.show', [$menu_id, 'mode=trash-restore']), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermission('menus.restore'))
        {!! renderActionIcon(
            route('cmsadmin.menus.restore', ['id' => $menu_id]),
            'recycle text-warning',
            __('common::crud.restore'),
            'text-danger btn-trash-restore',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('menus.permanentDestroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.permanent_delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$menu_id', 'permanent')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
