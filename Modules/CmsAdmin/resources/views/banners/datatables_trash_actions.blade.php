{!! Form::open([
    'route' => ['cmsadmin.banners.permanentDestroy', $banner_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $banner_id,
]) !!}
<div class='action-buttons action-col-2'>
    @if (checkCmsAdminPermission('banners.show'))
        {!! renderActionIcon(route('cmsadmin.banners.show', [$banner_id, 'mode=trash-restore']), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermission('banners.restore'))
        {!! renderActionIcon(
            route('cmsadmin.banners.restore', ['id' => $banner_id]),
            'recycle text-warning',
            __('common::crud.restore'),
            'text-danger btn-trash-restore',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('banners.permanentDestroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.permanent_delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$banner_id', 'permanent')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
