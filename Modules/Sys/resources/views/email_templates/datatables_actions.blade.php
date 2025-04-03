{!! Form::open([
    'route' => ['sys.emailTemplates.destroy', $template_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $template_id,
]) !!}
<input type="hidden" name="id" value="{{ $template_id }}" />
<div class='action-buttons'>
    @if (checkSysPermission('emailTemplates.show'))
        {!! renderActionIcon(route('sys.emailTemplates.show', $template_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkSysPermissionList(['emailTemplates.edit', 'emailTemplates.update']))
        {!! renderActionIcon(route('sys.emailTemplates.edit', $template_id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if (checkSysPermission('emailTemplates.regenerate'))
        {!! renderActionIconWithId(
            $template_id,
            route('sys.emailTemplates.regenerate'),
            'retweet',
            __('common::crud.regenerate'),
            'info',
            'sm btn-regenerate',
        ) !!}
    @endif
    @if (checkSysPermission('emailTemplates.destroy') && $reserved == 2)
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            ' title' => __('common::crud.delete'),
            'class' => 'btn text-warning btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$template_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
