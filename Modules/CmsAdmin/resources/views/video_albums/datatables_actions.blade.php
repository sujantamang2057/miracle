{!! Form::open([
    'route' => ['cmsadmin.videoAlbums.destroy', $album_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $album_id,
]) !!}
<input type="hidden" name="id" value="{{ $album_id }}" />
<div class='action-buttons action-col-4'>
    @if (checkCmsAdminPermission('videoAlbums.show'))
        {!! renderActionIcon(route('cmsadmin.videoAlbums.show', $album_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermissionList(['videoAlbums.edit', 'videoAlbums.update']))
        {!! renderActionIcon(route('cmsadmin.videoAlbums.edit', $album_id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if (checkMenuAccess('videoGalleries', 'cmsAdmin'))
        {!! renderActionIcon(
            route('cmsadmin.videoGalleries.index', $album_id),
            'images far',
            __('cmsadmin::models/video_galleries.singular'),
            'text-secondary',
            'sm',
        ) !!}
    @endif
    @if ($reserved == 2 && checkCmsAdminPermission('videoAlbums.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            ' title' => __('common::crud.delete'),
            'class' => 'btn text-warning btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$album_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
