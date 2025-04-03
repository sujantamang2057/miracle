{!! Form::open([
    'route' => ['cmsadmin.blocks.destroy', $block_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $block_id,
]) !!}
<input type="hidden" name="id" value="{{ $block_id }}" />
<div class='action-buttons action-col-5'>
    @if (checkCmsAdminPermission('blocks.show'))
        {!! renderActionIcon(route('cmsadmin.blocks.show', $block_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermissionList(['blocks.edit', 'blocks.update']))
        {!! renderActionIcon(route('cmsadmin.blocks.edit', $block_id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if (checkCmsAdminPermission('blocks.regenerate'))
        {!! renderActionIconWithId(
            $block_id,
            route('cmsadmin.blocks.regenerate'),
            'retweet',
            __('common::crud.regenerate'),
            'info',
            'sm btn-regenerate',
        ) !!}
    @endif
    @if ($reserved == 2 && checkCmsAdminPermission('blocks.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.delete'),
            'class' => 'btn text-warning btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$block_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
