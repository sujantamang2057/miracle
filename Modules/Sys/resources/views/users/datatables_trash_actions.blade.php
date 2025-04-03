{!! Form::open([
    'route' => ['sys.users.permanentDestroy', $id],
    'method' => 'delete',
    'id' => 'deleteform_' . $id,
]) !!}
<div class='action-buttons action-col-2'>
    @if (checkSysPermission('users.show'))
        {!! renderActionIcon(route('sys.users.show', [$id, 'mode=trash-restore']), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkSysPermission('users.restore'))
        {!! renderActionIcon(
            route('sys.users.restore', ['id' => $id]),
            'recycle text-warning',
            __('common::crud.restore'),
            'text-danger btn-trash-restore',
            'sm',
        ) !!}
    @endif
    @if (checkSysPermission('users.permanentDestroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.permanent_delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$id', 'permanent')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}

