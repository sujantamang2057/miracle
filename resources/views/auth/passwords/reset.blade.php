@extends('layouts.auth')

@section('content')

    <body class="hold-transition login-page">
        <div class="auth-container d-flex w-100">
            <div class="container-fluid align-self-center mx-auto">
                <div class="row">
                    <div class="col-6 d-lg-flex d-none h-100 justify-content-center flex-column start-0 top-0 my-auto text-center">
                        <div class="auth-cover-bg-image"></div>
                        <div class="auth-overlay"></div>
                        <div class="auth-cover">
                            <div class="position-relative">
                                <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}" />
                                <h1 class="font-weight-bolder text-uppercase mt-5 px-2 text-white">{{ config('app.name') }}</h1>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center ms-lg-auto me-lg-0 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <h1 class="mb-3 text-center"><a href="{{ url('/') }}" class="text-dark">{{ config('app.name') }}</a></h1>
                                <p class="login-box-msg mb-3">{{ __('common::passwords.reset_password_title') }}</p>

                                <form action="{{ route('password.update') }}" method="POST">
                                    @csrf

                                    @php
                                        if (!isset($token)) {
                                            $token = \Request::route('token');
                                        }
                                    @endphp

                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="input-group mb-3">
                                        <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('common::auth.email') }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                                        </div>
                                        @if ($errors->has('email'))
                                            <span class="error invalid-feedback">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('common::auth.password') }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                                        </div>
                                        @if ($errors->has('password'))
                                            <span class="error invalid-feedback">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="password" name="password_confirmation" class="form-control"
                                            placeholder="{{ __('common::passwords.confirm_password') }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                                        </div>
                                        @if ($errors->has('password_confirmation'))
                                            <span class="error invalid-feedback">{{ $errors->first('password_confirmation') }}</span>
                                        @endif
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit"
                                                class="btn btn-dark btn-block mb-3">{{ __('common::passwords.reset_password_button') }}</button>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                </form>
                                <hr>

                                <p class="mb-3 text-center">
                                    <a href="{{ route('login') }}" class="text-secondary">{{ __('common::passwords.login') }}</a>
                                </p>
                            </div>
                            <!-- /.login-card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
