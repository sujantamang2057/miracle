{!! Form::open([
    'route' => ['cmsadmin.posts.permanentDestroy', $post_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $post_id,
]) !!}

<div class='action-buttons action-col-2'>
    @if (checkCmsAdminPermission('posts.show'))
        {!! renderActionIcon(route('cmsadmin.posts.show', [$post_id, 'mode=trash-restore']), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermission('posts.restore'))
        {!! renderActionIcon(
            route('cmsadmin.posts.restore', ['id' => $post_id]),
            'recycle text-warning',
            __('common::crud.restore'),
            'text-danger btn-trash-restore',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('posts.permanentDestroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.permanent_delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$post_id', 'permanent')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}

