{!! Form::open([
    'route' => ['sys.users.destroy', $id],
    'method' => 'delete',
    'id' => 'deleteform_' . $id,
]) !!}
<input type="hidden" name="id" value="{{ $id }}" />
<div class='action-buttons'>
    @if (checkSysPermission('users.show'))
        {!! renderActionIcon(route('sys.users.show', $id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkSysPermissionList(['users.edit', 'users.update']))
        {!! renderActionIcon(route('sys.users.edit', $id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if (checkSysPermissionList(['users.changePassword', 'users.updatePassword']))
        @if ($id != 1 || auth()->user()->id == $id)
            {!! renderActionIcon(route('sys.users.changePassword', $id), 'lock', __('common::crud.change_password'), 'dark', 'sm') !!}
        @endif
    @endif
    @if (checkSysPermission('users.destroy'))
        @if ($id != 1 && auth()->user()->id != $id)
            {!! Form::button('<i class="fa fa-trash"></i>', [
                'type' => 'submit',
                ' title' => __('common::crud.delete'),
                'class' => 'btn text-warning btn-sm',
                'onclick' => "return confirmDelete(event, 'deleteform_$id')",
            ]) !!}
        @endif
    @endif
</div>
{!! Form::close() !!}
