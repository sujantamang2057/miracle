{!! Form::open([
    'route' => ['cmsadmin.videoGalleries.destroy', ['videoAlbum' => $album_id, 'gallery' => $video_id]],
    'method' => 'delete',
    'id' => 'deleteform_' . $video_id,
]) !!}
<input type="hidden" name="id" value="{{ $video_id }}" />
<div class='action-buttons'>
    @if (checkCmsAdminPermission('videoGalleries.show'))
        {!! renderActionIcon(
            route('cmsadmin.videoGalleries.show', ['videoAlbum' => $album_id, 'gallery' => $video_id]),
            'eye',
            __('common::crud.view'),
            'text-success',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermissionList(['videoGalleries.edit', 'videoGalleries.update']))
        {!! renderActionIcon(
            route('cmsadmin.videoGalleries.edit', ['videoAlbum' => $album_id, 'gallery' => $video_id]),
            'pen',
            __('common::crud.edit'),
            'text-primary',
            'sm',
        ) !!}
    @endif
    @if ($reserved == 2 && checkCmsAdminPermission('videoGalleries.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            ' title' => __('common::crud.delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$video_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
