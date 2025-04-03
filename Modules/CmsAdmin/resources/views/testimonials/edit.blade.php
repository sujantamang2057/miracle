@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('testimonial_edit', $testimonial) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('common::crud.edit') }} {{ __('cmsadmin::models/testimonials.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-7">
            {!! Form::model($testimonial, [
                'route' => ['cmsadmin.testimonials.update', $testimonial->testimonial_id],
                'method' => 'patch',
            ]) !!}
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::testimonials.fields')
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex">
                    <!-- Publish Field -->
                    <div class="form-group {{ validationClass($errors->has('publish')) }}">
                        <div class="form-check mr-3 pl-0">
                            {!! Form::label('publish', __('common::crud.fields.publish') . ':') !!}
                            @if (checkCmsAdminPermission('testimonials.togglePublish'))
                                {!! Form::hidden('publish', 2) !!}
                                {!! renderBootstrapSwitchPublish('publish', $id, $publish, old('publish')) !!}
                            @else
                                {{ getPublishText(2) }}
                            @endif
                        </div>
                        {{ validationMessage($errors->first('publish')) }}
                    </div>

                    <!-- Reserved Field -->
                    <div class="form-group {{ validationClass($errors->has('reserved')) }}">
                        <div class="form-check mr-3 pl-0">
                            {!! Form::label('reserved', __('common::crud.fields.reserved') . ':') !!}
                            @if (checkCmsAdminPermission('testimonials.toggleReserved'))
                                {!! Form::hidden('reserved', 2) !!}
                                {!! renderBootstrapSwitchReserved('reserved', $id, $reserved, old('reserved')) !!}
                            @else
                                {{ getReservedText(2) }}
                            @endif
                        </div>
                        {{ validationMessage($errors->first('reserved')) }}
                    </div>
                </div>
                {!! renderSubmitButton(__('common::crud.update'), 'success', '') !!}
                {!! renderLinkButton(__('common::crud.cancel'), route('cmsadmin.testimonials.index'), 'times', 'warning', '') !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
