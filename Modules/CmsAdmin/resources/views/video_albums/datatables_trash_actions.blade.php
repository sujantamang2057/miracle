{!! Form::open([
    'route' => ['cmsadmin.videoAlbums.permanentDestroy', $album_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $album_id,
]) !!}
<div class='action-buttons action-col-2'>
    @if (checkCmsAdminPermission('videoAlbums.show'))
        {!! renderActionIcon(
            route('cmsadmin.videoAlbums.show', [$album_id, 'mode=trash-restore']),
            'eye',
            __('common::crud.view'),
            'text-success',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('videoAlbums.restore'))
        {!! renderActionIcon(
            route('cmsadmin.videoAlbums.restore', ['id' => $album_id]),
            'recycle text-warning',
            __('common::crud.restore'),
            'text-danger btn-trash-restore',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('videoAlbums.permanentDestroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.permanent_delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$album_id', 'permanent')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}

