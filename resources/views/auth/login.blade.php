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
                                <h1 class="font-weight-bolder text-uppercase mt-5 px-2 text-white">
                                    {{ config('app.name') }}
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center ms-lg-auto me-lg-0 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <h1 class="mb-3 text-center">
                                    <a href="{{ url('/') }}" class="text-dark">{{ config('app.name') }}</a>
                                </h1>
                                <p class="login-box-msg mb-3">
                                    {{ __('common::auth.login_form_title') }}
                                </p>
                                <form method="post" action="{{ url('/login') }}">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('common::auth.email') }}"
                                            class="form-control @error('email') is-invalid @enderror" />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-envelope"></span>
                                            </div>
                                        </div>
                                        @error('email')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="password" name="password" placeholder="{{ __('common::auth.password') }}"
                                            class="form-control @error('password') is-invalid @enderror" />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-lock"></span>
                                            </div>
                                        </div>
                                        @error('password')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="icheck-primary mb-3">
                                                <input type="checkbox" id="remember" />
                                                <label for="remember">{{ __('common::auth.remember_me') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary btn-block mb-3">
                                                {{ __('common::auth.sign_in') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <hr />
                                <p class="mb-3 text-center">
                                    <a href="{{ route('password.request') }}" class="text-secondary">{{ __('common::auth.forgot_password') }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
