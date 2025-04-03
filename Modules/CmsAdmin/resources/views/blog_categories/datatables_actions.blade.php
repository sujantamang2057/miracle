{!! Form::open([
    'route' => ['cmsadmin.blogCategories.destroy', $cat_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $cat_id,
]) !!}
<input type="hidden" name="id" value="{{ $cat_id }}" />
<div class='action-buttons action-col-4'>
    @if (checkCmsAdminPermission('blogCategories.show'))
        {!! renderActionIcon(route('cmsadmin.blogCategories.show', $cat_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermissionList(['blogCategories.edit', 'blogCategories.update']))
        {!! renderActionIcon(route('cmsadmin.blogCategories.edit', $cat_id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if ($reserved == 2 && checkCmsAdminPermission('blogCategories.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.delete'),
            'class' => 'btn text-warning btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$cat_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
