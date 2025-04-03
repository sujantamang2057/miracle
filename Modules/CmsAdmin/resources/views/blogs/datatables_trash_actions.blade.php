{!! Form::open([
    'route' => ['cmsadmin.blogs.permanentDestroy', $blog_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $blog_id,
]) !!}

<div class='action-buttons action-col-2'>
    @if (checkCmsAdminPermission('blogs.show'))
        {!! renderActionIcon(route('cmsadmin.blogs.show', [$blog_id, 'mode=trash-restore']), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermission('blogs.restore'))
        {!! renderActionIcon(
            route('cmsadmin.blogs.restore', ['id' => $blog_id]),
            'recycle text-warning',
            __('common::crud.restore'),
            'text-danger btn-trash-restore',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('blogs.permanentdestroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.permanent_delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$blog_id', 'permanent')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
