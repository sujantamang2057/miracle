@extends('layouts.auth')

@section('content')

    <body class="hold-transition register-page">
        <div class="auth-container d-flex w-100">
            <div class="container-fluid align-self-center mx-auto">
                <div class="row">
                    <div class="col-6 d-lg-flex d-none h-100 justify-content-center flex-column start-0 top-0 my-auto text-center">
                        <div class="auth-cover-bg-image"></div>
                        <div class="auth-overlay"></div>
                        <div class="auth-cover">
                            <div class="position-relative">
                                <img src="../img/laravel.svg" alt="Laravel" />
                                <h1 class="font-weight-bolder text-uppercase mt-5 px-2 text-white">
                                    {{ config('app.name') }}
                                </h1>
                                <p class="px-2 text-white">
                                    Crafted on top of PHP framework.
                                </p>
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
                                    Register a new membership
                                </p>
                                <form method="post" action="{{ route('register') }}">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" placeholder="Full name" />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror" placeholder="Email" />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-envelope"></span>
                                            </div>
                                        </div>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Password" />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-lock"></span>
                                            </div>
                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Retype password" />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-lock"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="icheck-primary mb-3">
                                                <input type="checkbox" id="agreeTerms" name="terms" value="agree" />
                                                <label for="agreeTerms">
                                                    I agree to the
                                                    <a href="#" class="text-secondary">Terms and Conditions</a>
                                                </label>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-secondary btn-block mb-3">
                                                Register
                                            </button>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                </form>
                                <hr />

                                <p class="mb-0 text-center">
                                    Already have an account ?
                                    <a href="{{ route('login') }}" class="text-secondary text-center">Sign In</a>
                                </p>
                            </div>
                            <!-- /.form-box -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>
    @endsection
