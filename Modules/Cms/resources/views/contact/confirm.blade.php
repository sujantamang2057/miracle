@extends('cms::layouts.master')

@section('content_header')
    <section class="sc-breadcrumb">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('cms.home.index') }}">{{ __('cms::general.home') }}</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('cms.contact.index') }}">{{ __('cms::general.contact_us') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('cms::general.confirm') }}</li>
                </ol>
            </nav>
        </div>
    </section>
@endsection

@section('content')
    <div class="contact-confirm-page">
        <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
            <div class="heading d-flex align-items-end justify-content-between mb-sm-5 pb-sm-4 mb-3 pb-1">
                <div class="">
                    <div class="sc-title text-uppercase fn-raleway">{{ __('cms::contact.contact_title') }}</div>
                </div>
            </div>
        </div>

        <section class="sc-contact-page py-md-5 py-3">
            <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
                <div class="contact-box mb-3">
                    <h5 class="fw-semibold mb-0 py-3">{{ __('cms::contact.inquiry') }}</h5>

                    {!! Form::open(['route' => 'cms.contact.complete', 'class' => '']) !!}
                    <div class="contact-confirm-wrapper p-sm-4 p-3 shadow-sm">

                        {!! Form::hidden('confirmed', true) !!}
                        <div class="row">
                            <div class="col-sm-3 col-lg-2 border-end">
                                <label class="form-label">{{ __('cms::contact.fields.fullname') }}</label>
                            </div>
                            <div class="col-sm-9 col-lg-6 ps-5">
                                <div>{{ $name . ' ' . $surname }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-lg-2 border-end">
                                <label class="form-label">{{ __('cms::contact.fields.email') }}</label>
                            </div>
                            <div class="col-sm-9 col-lg-6 ps-5">
                                <div>{{ $email }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-lg-2 border-end">
                                <label class="form-label">{{ __('cms::contact.fields.phone_no') }}</label>
                            </div>
                            <div class="col-sm-9 col-lg-10 ps-5">
                                <div class="d-flex gap-3">
                                    <span>{{ $code }}</span> <span>{{ $phone_no }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-lg-2 border-end">
                                <label class="form-label">{{ __('cms::contact.fields.message') }}</label>
                            </div>
                            <div class="col-sm-9 col-lg-10 ps-5">
                                <div>{!! nl2br($message_body) !!}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="contact-button d-flex flex-column align-items-center justify-content-center">
                    <button class="btn-contact rounded-pill mb-sm-5 mt-sm-5 mb-4 mt-3 border border-0 shadow"
                        type="submit"><span>{{ __('cms::contact.confirm') }}</span></button>
                    <a href="{{ route('cms.contact.index') }}" class="btn-back rounded-pill mb-sm-5 mb-4 shadow">
                        <span>{{ __('cms::contact.edit') }}</span></a>
                </div>
                {!! Form::close() !!}
        </section>
    </div>
@endsection
