{!! Form::open([
    'route' => ['sys.sns.destroy', $sns_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $sns_id,
]) !!}
<input type="hidden" name="id" value="{{ $sns_id }}" />
<div class='action-buttons'>
    @if (checkSysPermission('sns.show'))
        {!! renderActionIcon(route('sys.sns.show', $sns_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkSysPermissionList(['sns.edit', 'sns.update']))
        {!! renderActionIcon(route('sys.sns.edit', $sns_id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if ($reserved == 2 && checkSysPermission('sns.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            ' title' => __('common::crud.delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$sns_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
