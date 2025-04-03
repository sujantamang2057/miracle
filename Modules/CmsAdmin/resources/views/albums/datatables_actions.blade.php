{!! Form::open([
    'route' => ['cmsadmin.albums.destroy', $album_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $album_id,
]) !!}
<input type="hidden" name="id" value="{{ $album_id }}" />
<div class='action-buttons action-col-4'>
    @if (checkCmsAdminPermission('albums.show'))
        {!! renderActionIcon(route('cmsadmin.albums.show', $album_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermissionList(['albums.edit', 'albums.update']))
        {!! renderActionIcon(route('cmsadmin.albums.edit', $album_id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if (checkCmsAdminPermissionList(['galleries.index', 'galleries.editable', 'galleries.destroy', 'galleries.togglePublish']))
        {!! renderActionIcon(route('cmsadmin.galleries.index', $album_id), 'images far', __('cmsadmin::models/galleries.singular'), 'secondary', 'sm') !!}
    @endif
    @if ($reserved == 2 && checkCmsAdminPermission('albums.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            ' title' => __('common::crud.delete'),
            'class' => 'btn text-warning btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$album_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
