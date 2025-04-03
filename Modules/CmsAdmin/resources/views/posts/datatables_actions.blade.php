{!! Form::open([
    'route' => ['cmsadmin.posts.destroy', $post_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $post_id,
]) !!}
<input type="hidden" name="id" value="{{ $post_id }}" />
<div class='action-buttons'>
    @if (checkCmsAdminPermission('posts.show'))
        {!! renderActionIcon(route('cmsadmin.posts.show', $post_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermissionList(['posts.edit', 'posts.update']))
        {!! renderActionIcon(route('cmsadmin.posts.edit', $post_id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if (checkCmsAdminPermissionList('posts.multidata'))
        {!! renderActionIcon(route('cmsadmin.posts.multidata', $post_id), 'clone', __('common::multidata.name'), 'text-secondary', 'sm') !!}
    @endif

    @if (checkMenuAccess('postDetails', 'cmsAdmin'))
        {!! renderActionIcon(
            route('cmsadmin.postDetails.index', $post_id),
            'list',
            __('cmsadmin::models/posts.post_multidata.singular'),
            'secondary',
            'sm',
        ) !!}
    @endif

    @if (checkCmsAdminPermission('posts.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.delete'),
            'class' => 'btn text-warning btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$post_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
