@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('page_clone') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('common::crud.clone') }} {{ __('cmsadmin::models/pages.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-7">
            {!! Form::model($page, ['route' => ['cmsadmin.pages.store'], 'method' => 'post']) !!}
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::pages.fields')
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex">
                    <!-- Publish Field -->
                    <div class="form-group {{ validationClass($errors->has('publish')) }}">
                        <div class="form-check mr-3 pl-0">
                            {!! Form::label('publish', __('common::crud.fields.publish') . ':') !!}
                            @if (checkCmsAdminPermission('pages.togglePublish'))
                                {!! Form::hidden('publish', 2) !!}
                                {!! renderBootstrapSwitchPublish('publish', $id, $publish, old('publish')) !!}
                                {{ validationMessage($errors->first('publish')) }}
                            @else
                                {{ getPublishText(2) }}
                            @endif
                        </div>
                    </div>

                    <!-- Reserved Field -->
                    <div class="form-group {{ validationClass($errors->has('reserved')) }}">
                        <div class="form-check mr-3 pl-0">
                            {!! Form::label('reserved', __('common::crud.fields.reserved') . ':') !!}
                            @if (checkCmsAdminPermission('pages.toggleReserved'))
                                {!! Form::hidden('reserved', 2) !!}
                                {!! renderBootstrapSwitchReserved('reserved', $id, $reserved, old('reserved')) !!}
                                {{ validationMessage($errors->first('reserved')) }}
                            @else
                                {{ getReservedText(2) }}
                            @endif
                        </div>
                    </div>
                </div>
                {!! renderSubmitButton(__('common::crud.create'), 'primary', '') !!}
                {!! renderLinkButton(__('common::crud.cancel'), route('cmsadmin.pages.index'), 'times', 'warning', '') !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            @if (old('page_type') == 2)
                destroyTinyMce();
            @endif
            $("#page_type").on('change', function(e) {
                if ($(this).val() == 2) {
                    destroyTinyMce();
                } else {
                    initTinyMce();
                }
            });
        })
    </script>
@endpush
