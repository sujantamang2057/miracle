@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('rbac_permission_scan') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1> {{ __('sys::models/permissions.text.scan_route_permissions') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-7">
            {!! Form::open(['route' => 'sys.permissions.storeScannedRoutePermissions']) !!}
            <div class="card-body">
                <p>{{ __('sys::models/permissions.text.scan_modules_text') }}</p>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group required {{ validationClass($errors->has('module_name')) }}">
                            {!! Form::label('module_name', __('sys::models/permissions.fields.module_name') . ':') !!}
                            {!! Form::select('module_name', $moduleList, null, [
                                'class' => 'form-control ' . validationInputClass($errors->has('module_name')),
                            ]) !!}
                            {{ validationMessage($errors->first('module_name')) }}
                        </div>
                    </div>
                    <div class="col-sm-12">
                        {!! renderSubmitButton(__('sys::models/permissions.text.scan'), 'success', '') !!}
                        {!! renderLinkButton(__('common::crud.cancel'), route('sys.rbac.index'), 'times', 'warning', '') !!}
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
