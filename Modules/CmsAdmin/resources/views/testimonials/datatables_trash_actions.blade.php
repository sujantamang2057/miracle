{!! Form::open([
    'route' => ['cmsadmin.testimonials.permanentDestroy', $testimonial_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $testimonial_id,
]) !!}
<div class='action-buttons action-col-2'>
    @if (checkCmsAdminPermission('testimonials.show'))
        {!! renderActionIcon(
            route('cmsadmin.testimonials.show', [$testimonial_id, 'mode=trash-restore']),
            'eye',
            __('common::crud.view'),
            'text-success',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('testimonials.restore'))
        {!! renderActionIcon(
            route('cmsadmin.testimonials.restore', ['id' => $testimonial_id]),
            'recycle text-warning',
            __('common::crud.restore'),
            'text-danger btn-trash-restore',
            'sm',
        ) !!}
    @endif
    @if (checkCmsAdminPermission('testimonials.permanentDestroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'title' => __('common::crud.permanent_delete'),
            'class' => 'btn text-danger btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$testimonial_id', 'permanent')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}

