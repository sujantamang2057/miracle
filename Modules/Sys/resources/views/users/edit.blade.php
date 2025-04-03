@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('user_edit', $user) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('common::crud.edit') }} {{ __('sys::models/users.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            {!! Form::model($user, ['route' => ['sys.users.update', $user->id], 'method' => 'patch']) !!}
            <div class="card-body">
                <div class="row">
                    @include('sys::users.fields')
                </div>
                <div class="d-flex">
                    <!-- Active Field -->
                    @if (auth()->user()->id != $id)
                        <div class="form-group {{ validationClass($errors->has('active')) }}">
                            <div class="form-check mr-3 pl-0">
                                {!! Form::label('active', __('sys::models/users.fields.active') . ':') !!}
                                @if (checkSysPermission('users.togglePublish'))
                                    {!! Form::hidden('active', 2) !!}
                                    {!! renderBootstrapSwitchActive('active', $id, $active, old('active')) !!}
                                    {{ validationMessage($errors->first('active')) }}
                                @else
                                    {{ getActiveText(2) }}
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="form-group col-md-6 readonly">
                            {!! Form::hidden('active', $activeText) !!}
                            <div class="bg-light px-2 pt-2">{!! Form::label('active', __('sys::models/users.fields.active') . ': ') !!}
                                <span>{{ getActiveText($user->active) }}</span>
                            </div>
                        </div>
                    @endif
                </div>
                {!! renderSubmitButton(__('common::crud.update'), 'primary', '') !!}
                {!! renderLinkButton(__('common::crud.cancel'), route('sys.users.index'), 'times', 'warning', '') !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
