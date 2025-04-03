{!! Form::open([
    'route' => ['cmsadmin.banners.destroy', $banner_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $banner_id,
]) !!}
<input type="hidden" name="id" value="{{ $banner_id }}" />
<div class='action-buttons'>
    @if (checkCmsAdminPermission('banners.show'))
        {!! renderActionIcon(route('cmsadmin.banners.show', $banner_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermissionList(['banners.edit', 'banners.update']))
        {!! renderActionIcon(route('cmsadmin.banners.edit', $banner_id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if ($reserved == 2 && checkCmsAdminPermission('banners.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.delete'),
            'class' => 'btn text-warning btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$banner_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
