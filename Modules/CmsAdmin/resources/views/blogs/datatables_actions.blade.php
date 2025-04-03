{!! Form::open([
    'route' => ['cmsadmin.blogs.destroy', $blog_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $blog_id,
]) !!}
<input type="hidden" name="id" value="{{ $blog_id }}" />
<div class='action-buttons'>
    @if (checkCmsAdminPermission('blogs.show'))
        {!! renderActionIcon(route('cmsadmin.blogs.show', $blog_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermissionList(['blogs.edit', 'blogs.update']))
        {!! renderActionIcon(route('cmsadmin.blogs.edit', $blog_id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if (checkCmsAdminPermission('blogs.multidata'))
        {!! renderActionIcon(route('cmsadmin.blogs.multidata', $blog_id), 'clone', __('common::multidata.name'), 'text-secondary', 'sm') !!}
    @endif
    @if (checkMenuAccess('blogDetails', 'cmsAdmin'))
        {!! renderActionIcon(
            route('cmsadmin.blogDetails.index', $blog_id),
            'list',
            __('cmsadmin::models/blogs.blog_multidata.singular'),
            'secondary',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('blogs.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.delete'),
            'class' => 'btn text-warning btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$blog_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
