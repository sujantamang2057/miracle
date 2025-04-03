{!! Form::open([
    'route' => ['cmsadmin.pages.destroy', $page_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $page_id,
]) !!}
<input type="hidden" name="id" value="{{ $page_id }}" />
<div class='action-buttons action-col-4'>
    @if (checkCmsAdminPermission('pages.show'))
        {!! renderActionIcon(route('cmsadmin.pages.show', $page_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif

    @if (checkCmsAdminPermissionList(['pages.edit', 'pages.update']))
        {!! renderActionIcon(route('cmsadmin.pages.edit', $page_id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif

    @if (checkCmsAdminPermissionList('pages.multidata'))
        {!! renderActionIcon(route('cmsadmin.pages.multidata', $page_id), 'clone', __('common::multidata.name'), 'text-secondary', 'sm') !!}
    @endif

    @if (checkMenuAccess('pageDetails', 'cmsAdmin'))
        {!! renderActionIcon(
            route('cmsadmin.pageDetails.index', $page_id),
            'list',
            __('cmsadmin::models/pages.page_multidata.singular'),
            'secondary',
            'sm',
        ) !!}
    @endif

    @if ($page_type == 2 && checkCmsAdminPermission('pages.regenerate'))
        {!! renderActionIconWithId(
            $page_id,
            route('cmsadmin.pages.regenerate'),
            'retweet',
            __('common::crud.regenerate'),
            'text-info',
            'sm btn-regenerate',
        ) !!}
    @endif
    @if ($reserved == 2 && checkCmsAdminPermission('pages.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            ' title' => __('common::crud.delete'),
            'class' => 'btn text-warning btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$page_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
