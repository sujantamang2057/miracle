{!! Form::open([
    'route' => ['cmsadmin.seos.permanentDestroy', $id],
    'method' => 'delete',
    'id' => 'deleteform_' . $id,
]) !!}
<div class='action-buttons action-col-2'>
    @if (checkCmsAdminPermission('seos.show'))
        {!! renderActionIcon(route('cmsadmin.seos.show', [$id, 'mode=trash-restore']), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermission('seos.restore'))
        {!! renderActionIcon(
            route('cmsadmin.seos.restore', ['id' => $id]),
            'recycle text-warning',
            __('common::crud.restore'),
            'text-danger btn-trash-restore',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('seos.permanentDestroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.permanent_delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$id', 'permanent')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
