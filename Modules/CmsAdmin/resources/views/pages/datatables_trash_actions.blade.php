{!! Form::open([
    'route' => ['cmsadmin.pages.permanentDestroy', $page_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $page_id,
]) !!}
<div class='action-buttons action-col-2'>
    @if (checkCmsAdminPermission('pages.show'))
        {!! renderActionIcon(route('cmsadmin.pages.show', [$page_id, 'mode=trash-restore']), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermission('pages.restore'))
        {!! renderActionIcon(
            route('cmsadmin.pages.restore', ['id' => $page_id]),
            'recycle text-warning',
            __('common::crud.restore'),
            'text-danger btn-trash-restore',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('pages.permanentDestroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.permanent_delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$page_id', 'permanent')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}

