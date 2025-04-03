{!! Form::open([
    'route' => ['cmsadmin.resources.destroy', $resource_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $resource_id,
]) !!}
<input type="hidden" name="id" value="{{ $resource_id }}" />
<div class='action-buttons'>
    @if (checkCmsAdminPermission('resources.show'))
        {!! renderActionIcon(route('cmsadmin.resources.show', $resource_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermissionList(['resources.edit', 'resources.update']))
        {!! renderActionIcon(route('cmsadmin.resources.edit', $resource_id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if ($reserved == 2 && checkCmsAdminPermission('resources.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            ' title' => __('common::crud.delete'),
            'class' => 'btn text-warning btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$resource_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
