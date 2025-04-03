@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('user_profile_change_password', $user) }}
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
            {!! Form::model($user, ['route' => ['sys.profile.updatePassword'], 'method' => 'patch']) !!}
            <div class="card-body">
                <div class="row">
                    @include('sys::profile.fields_change_password')
                </div>

                {!! renderSubmitButton(__('common::crud.save'), 'success', '') !!}
                {!! renderLinkButton(__('common::crud.cancel'), route('sys.profile.index'), 'times', 'warning', '') !!}

            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
