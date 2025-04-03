{!! Form::open([
    'route' => ['sys.roles.destroy', $id],
    'method' => 'delete',
    'id' => 'deleteform_' . $id,
]) !!}
<input type="hidden" name="id" value="{{ $id }}" />
<div class='action-buttons'>
    {!! renderActionIcon(route('sys.roles.edit', $id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @if (!in_array($name, ROLES_ORDER_LIST))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            ' title' => __('common::crud.delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
