{!! Form::open([
    'route' => ['cmsadmin.newsCategories.destroy', $category_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $category_id,
]) !!}
<input type="hidden" name="id" value="{{ $category_id }}" />
<div class='action-buttons'>
    @if (checkCmsAdminPermission('newsCategories.show'))
        {!! renderActionIcon(route('cmsadmin.newsCategories.show', $category_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermissionList(['newsCategories.edit', 'newsCategories.update']))
        {!! renderActionIcon(route('cmsadmin.newsCategories.edit', $category_id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if ($reserved == 2 && checkCmsAdminPermission('newsCategories.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            ' title' => __('common::crud.delete'),
            'class' => 'btn text-warning btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$category_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
