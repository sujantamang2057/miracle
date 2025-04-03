{!! Form::open([
    'route' => ['cmsadmin.seos.destroy', $id],
    'method' => 'delete',
    'id' => 'deleteform_' . $id,
]) !!}
<input type="hidden" name="id" value="{{ $id }}" />
<div class='action-buttons'>
    @if (checkCmsAdminPermission('seos.show'))
        {!! renderActionIcon(route('cmsadmin.seos.show', $id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermissionList(['seos.edit', 'seos.update']))
        {!! renderActionIcon(route('cmsadmin.seos.edit', $id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if (checkCmsAdminPermission('seos.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            ' title' => __('common::crud.delete'),
            'class' => 'btn text-warning btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
