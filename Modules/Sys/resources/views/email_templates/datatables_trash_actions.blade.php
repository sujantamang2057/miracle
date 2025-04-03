{!! Form::open([
    'route' => ['sys.emailTemplates.permanentDestroy', $template_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $template_id,
]) !!}
<div class='action-buttons action-col-2'>
    @if (checkSysPermission('emailTemplates.show'))
        {!! renderActionIcon(
            route('sys.emailTemplates.show', [$template_id, 'mode=trash-restore']),
            'eye',
            __('common::crud.view'),
            'text-success',
            'sm',
        ) !!}
    @endif
    @if (checkSysPermission('emailTemplates.restore'))
        {!! renderActionIcon(
            route('sys.emailTemplates.restore', ['id' => $template_id]),
            'recycle text-warning',
            __('common::crud.restore'),
            'text-danger btn-trash-restore',
            'sm',
        ) !!}
    @endif
    @if (checkSysPermission('emailTemplates.permanentDestroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.permanent_delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$template_id', 'permanent')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}


