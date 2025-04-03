{!! Form::open([
    'route' => ['cmsadmin.resources.permanentDestroy', $resource_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $resource_id,
]) !!}
<div class='action-buttons action-col-2'>
    @if (checkCmsAdminPermission('resources.show'))
        {!! renderActionIcon(
            route('cmsadmin.resources.show', [$resource_id, 'mode=trash-restore']),
            'eye',
            __('common::crud.view'),
            'text-success',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('resources.restore'))
        {!! renderActionIcon(
            route('cmsadmin.resources.restore', ['id' => $resource_id]),
            'recycle text-warning',
            __('common::crud.restore'),
            'text-danger btn-trash-restore',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('resources.permanentDestroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.permanent_delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$resource_id', 'permanent')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}

