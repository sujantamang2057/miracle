{!! Form::open([
    'route' => ['cmsadmin.blogDetails.destroy', ['blog' => $blog_id, 'blogDetail' => $detail_id]],
    'method' => 'delete',
    'id' => 'deleteform_' . $detail_id,
]) !!}
<input type="hidden" name="id" value="{{ $detail_id }}" />
<div class='action-buttons action-col-2'>
    @if (checkCmsadminPermission('blogDetails.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            ' title' => __('common::crud.delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$detail_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
