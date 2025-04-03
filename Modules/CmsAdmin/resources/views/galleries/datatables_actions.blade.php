{!! Form::open([
    'route' => ['cmsadmin.galleries.destroy', ['album' => $album_id, 'gallery' => $image_id]],
    'method' => 'delete',
    'id' => 'deleteform_' . $image_id,
]) !!}
<input type="hidden" name="id" value="{{ $image_id }}" />
<div class='action-buttons action-col-2'>
    @if (checkCmsAdminPermission('albums.setCoverImage'))
        @unless ($album['cover_image_id'] == $image_id)
            <a href="javascript:void();" class="set-cover-image btn text-warning btn-sm" title="Select Cover Image" data-id="{{ $image_id }}"
                data-album_id="{{ $album_id }}"><i class="far fa-check-circle"></i></a>
        @endunless
    @endif
    @if (checkCmsAdminPermission('galleries.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            ' title' => __('common::crud.delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$image_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
