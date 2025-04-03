{!! Form::open([
    'route' => ['cmsadmin.testimonials.destroy', $testimonial_id],
    'method' => 'delete',
    'id' => 'deleteform_' . $testimonial_id,
]) !!}
<input type="hidden" name="id" value="{{ $testimonial_id }}" />
<div class='action-buttons'>
    @if (checkCmsAdminPermission('testimonials.show'))
        {!! renderActionIcon(route('cmsadmin.testimonials.show', $testimonial_id), 'eye', __('common::crud.view'), 'text-success', 'sm') !!}
    @endif
    @if (checkCmsAdminPermissionList(['testimonials.edit', 'testimonials.update']))
        {!! renderActionIcon(route('cmsadmin.testimonials.edit', $testimonial_id), 'pen', __('common::crud.edit'), 'text-primary', 'sm') !!}
    @endif
    @if ($reserved == 2 && checkCmsAdminPermission('testimonials.destroy'))
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            ' title' => __('common::crud.delete'),
            'class' => 'btn text-warning btn-sm',
            'onclick' => "return confirmDelete(event, 'deleteform_$testimonial_id')",
        ]) !!}
    @endif
</div>
{!! Form::close() !!}
