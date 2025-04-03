@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('user_change_password', $user) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('sys::models/users.text.change_password') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-7">
            {!! Form::model($user, ['route' => ['sys.users.updatePassword', $user->id], 'method' => 'patch']) !!}
            <div class="card-body">
                <div class="row">
                    @include('sys::users.fields_change_password')
                </div>

                <div class="d-flex column-gap-5 mt-3">
                    {!! renderSubmitButton(__('common::crud.save'), 'success', '') !!}
                    {!! renderLinkButton(__('common::crud.cancel'), route('sys.users.index'), 'times', 'warning', '') !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
