@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('user_profile', $user) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('sys::models/users.text.profile_update') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @include('flash::message')
        <div class="clearfix"></div>
        <div class="card mb-7">
            {!! Form::model($user, ['route' => ['sys.profile.update'], 'method' => 'patch']) !!}
            <div class="card-body">
                <div class="row">
                    @include('sys::profile.form')
                </div>

                {!! renderSubmitButton(__('common::crud.update'), 'primary', '') !!}
                {!! renderLinkButton(__('common::crud.change_password'), route('sys.profile.changePassword'), 'lock', 'dark', '') !!}
                {!! renderLinkButton(__('common::crud.cancel'), route('home'), 'times', 'warning', '') !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
