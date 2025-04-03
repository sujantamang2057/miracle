{!! Form::open([
    'route' => ['cmsadmin.menus.destroy', $menu_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $menu_id,
]) !!}
<input type="hidden" name="id" value="{{ $menu_id }}" />
<div class='action-buttons'>
    @if (checkCmsAdminPermission('menus.show'))
        {!! renderActionIcon(route('cmsadmin.menus.show', $menu_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermissionList(['menus.edit', 'menus.update']))
        {!! renderActionIcon(route('cmsadmin.menus.edit', $menu_id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if ($reserved == 2 && checkCmsAdminPermission('menus.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            ' title' => __('common::crud.delete'),
            'class' => 'btn text-warning btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$menu_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
