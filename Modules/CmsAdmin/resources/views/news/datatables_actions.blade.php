{!! Form::open([
    'route' => ['cmsadmin.news.destroy', $news_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $news_id,
]) !!}
<input type="hidden" name="id" value="{{ $news_id }}" />
<div class='action-buttons'>
    @if (checkCmsAdminPermission('news.show'))
        {!! renderActionIcon(route('cmsadmin.news.show', $news_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermissionList(['news.edit', 'news.update']))
        {!! renderActionIcon(route('cmsadmin.news.edit', $news_id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if (checkCmsAdminPermission('news.multidata'))
        {!! renderActionIcon(route('cmsadmin.news.multidata', $news_id), 'clone', __('common::multidata.name'), 'secondary', 'sm') !!}
    @endif
    @if (checkMenuAccess('newsDetails', 'cmsAdmin'))
        {!! renderActionIcon(
            route('cmsadmin.newsDetails.index', $news_id),
            'list',
            __('cmsadmin::models/news.news_multidata.singular'),
            'secondary',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('news.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            ' title' => __('common::crud.delete'),
            'class' => 'btn text-warning btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$news_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
