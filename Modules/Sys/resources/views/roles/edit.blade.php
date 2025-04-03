@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('rbac_role_edit', $role) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('common::crud.edit') }} {{ __('sys::models/roles.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-7">
            {!! Form::model($role, [
                'route' => ['sys.roles.update', $role->id],
                'method' => 'patch',
                'id' => 'role-form',
            ]) !!}
            <div class="card-body">
                @include('sys::roles.fields')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                    {{ __('common::crud.update') }}</button>
                {!! renderLinkButton(__('common::crud.cancel'), route('sys.rbac.index'), 'times', 'warning', '') !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
