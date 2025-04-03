{!! Form::open([
    'route' => ['cmsadmin.news.permanentDestroy', $news_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $news_id,
]) !!}
<div class='action-buttons action-col-2'>
    @if (checkCmsAdminPermission('news.show'))
        {!! renderActionIcon(route('cmsadmin.news.show', [$news_id, 'mode=trash-restore']), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermission('news.restore'))
        {!! renderActionIcon(
            route('cmsadmin.news.restore', ['id' => $news_id]),
            'recycle text-warning',
            __('common::crud.restore'),
            'text-danger btn-trash-restore',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('news.permanentDestroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.permanent_delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$news_id', 'permanent')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}

