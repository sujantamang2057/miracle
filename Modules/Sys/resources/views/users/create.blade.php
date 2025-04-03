@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('user_create') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('common::crud.create') }} {{ __('sys::models/users.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            {!! Form::open(['route' => 'sys.users.store']) !!}
            <div class="card-body">
                <div class="row">
                    @include('sys::users.fields')
                </div>
                <div class="d-flex">
                    <!-- Active Field -->
                    <div class="form-group {{ validationClass($errors->has('active')) }}">
                        <div class="form-check mr-3 pl-0">
                            {!! Form::label('active', __('sys::models/users.fields.active') . ':') !!}
                            @if (checkSysPermission('users.toggleActive'))
                                {!! Form::hidden('active', 2) !!}
                                {!! renderBootstrapSwitchActive('active', $id, $active, old('active')) !!}
                                {{ validationMessage($errors->first('active')) }}
                            @else
                                {{ getActiveText(2) }}
                            @endif
                        </div>
                    </div>
                </div>
                {!! renderSubmitButton(__('common::crud.create'), 'success', '') !!}
                {!! renderLinkButton(__('common::crud.cancel'), route('sys.users.index'), 'times', 'warning', '') !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
