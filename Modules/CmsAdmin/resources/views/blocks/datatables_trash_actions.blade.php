{!! Form::open([
    'route' => ['cmsadmin.blocks.permanentDestroy', $block_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $block_id,
]) !!}
<div class='action-buttons action-col-2'>
    @if (checkCmsAdminPermission('blocks.show'))
        {!! renderActionIcon(route('cmsadmin.blocks.show', [$block_id, 'mode=trash-restore']), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermission('blocks.restore'))
        {!! renderActionIcon(
            route('cmsadmin.blocks.restore', ['id' => $block_id]),
            'recycle text-warning',
            __('common::crud.restore'),
            'text-danger btn-trash-restore',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('blocks.permanentDestroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.permanent_delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$block_id', 'permanent')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}

