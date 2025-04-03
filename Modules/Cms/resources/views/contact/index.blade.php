@extends('cms::layouts.master')

@section('content_header')
    <section class="sc-breadcrumb">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('cms.home.index') }}">{{ __('cms::general.home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('cms::general.contact_us') }}</li>
                </ol>
            </nav>
        </div>
    </section>
@endsection

@section('content')
    <div class="contact-page">
        <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
            <div class="heading d-flex align-items-end justify-content-between">
                <div class="mx-auto">
                    <div class="sc-title text-uppercase fn-raleway">{{ __('cms::contact.contact_title') }}</div>
                </div>
            </div>
            <section class="sc-contact-page pt-3">
                <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
                    <div class="contact-message pt-md-0 pt-lg-4 mb-2 py-4 pt-0 text-center">
                        <p class="para-text fs-5 fw">{!! __('cms::contact.look_forward_msg') !!}</p>
                    </div>
                    <div class="contact-box mb-3">
                        <h5 class="fw-semibold mb-0 py-3">{{ __('cms::contact.inquiry') }}</h5>

                        {!! Form::open(['route' => 'cms.contact.confirm']) !!}
                        <div class="contact-confirm-wrapper contact-box-wrapper">
                            <div class="row row-gap-2 align-items-lg-center">
                                <div class="col-12 col-sm-3 col-lg-2 border-end">
                                    <label class="form-label">{{ __('cms::contact.fields.fullname') }} <span class="required">*</span></label>
                                </div>
                                <div class="col-12 col-sm-9 col-lg ps-sm-0 ps-md-5 pe-sm-0 px-0">
                                    <div class="row row-gap-2">
                                        <div
                                            class="col-12 col-sm-12 col-lg d-flex gap-lg-4 mb-sm-0 px-sm-2 flex-column flex-md-row align-items-start align-items-md-center mb-3 gap-2 px-1">
                                            <div class="row w-100 flex-column align-items-start align-items flex-lg-row align-items-lg-center mb-2 gap-1">
                                                <div class="col col-md-3 col-lg-4 p-0">
                                                    <span>{{ __('cms::contact.fields.surname') }}</span>
                                                </div>
                                                <div class="col position-relative excep p-0">
                                                    {!! Form::text('surname', old('surname', $data['surname'] ?? ''), [
                                                        'class' => 'form-control ' . validationInputClass($errors->has('surname')),
                                                        'placeholder' => 'Last Name',
                                                        'maxlength' => 50,
                                                    ]) !!}
                                                    {{ validationMessage($errors->first('surname')) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="col-12 col-sm-12 col-lg d-flex gap-lg-4 mb-sm-0 px-sm-2 flex-column flex-md-row align-items-start align-items-md-center mb-3 gap-2 px-1">
                                            <div class="row w-100 flex-column align-items-start flex-lg-row align-items-lg-center gap-1">
                                                <div class="col col-md-3 col-lg-4 p-0">
                                                    <span>{{ __('cms::contact.fields.name') }}</span>
                                                </div>
                                                <div class="col position-relative excep p-0">
                                                    {!! Form::text('name', old('name', $data['name'] ?? ''), [
                                                        'class' => 'form-control w-100 ' . validationInputClass($errors->has('name')),
                                                        'placeholder' => 'First Name',
                                                        'maxlength' => 50,
                                                    ]) !!}
                                                    {{ validationMessage($errors->first('name')) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3 col-lg-2 border-end">
                                    <label class="form-label">{{ __('cms::contact.fields.email') }} <span class="required">*</span></label>
                                </div>
                                <div class="col-sm-9 col-lg ps-sm-2 ps-md-4 position-relative my-3 py-0">
                                    {!! Form::email('email', old('email', $data['email'] ?? ''), [
                                        'class' => 'form-control ' . validationInputClass($errors->has('email')),
                                        'placeholder' => 'test@gmail.com',
                                        'maxlength' => 150,
                                    ]) !!}
                                    {{ validationMessage($errors->first('email')) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 col-lg-2 border-end">
                                    <label class="form-label">{{ __('cms::contact.fields.phone_no') }} <span class="required">*</span></label>
                                </div>
                                <div class="col-sm-9 col-lg-10 ps-sm-2 ps-md-4 mb-lg-3">
                                    <div class="row align-items-start">
                                        <div class="col-sm-6 col-lg-3 position-relative">
                                            {!! Form::text('code', old('code', $data['code'] ?? ''), [
                                                'class' => 'form-control ' . validationInputClass($errors->has('code')),
                                                'placeholder' => '+977',
                                                'maxlength' => 4,
                                            ]) !!}
                                            {{ validationMessage($errors->first('code')) }}
                                        </div>
                                        <div class="col-sm-6 col-lg position-relative">
                                            {!! Form::text('phone_no', old('phone_no', $data['phone_no'] ?? ''), [
                                                'class' => 'form-control ' . validationInputClass($errors->has('phone_no')),
                                                'placeholder' => '1234567890',
                                                'maxlength' => 10,
                                            ]) !!}
                                            {{ validationMessage($errors->first('phone_no')) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 col-lg-2 border-end">
                                    <label class="form-label">{{ __('cms::contact.fields.message') }} <span class="required">*</span></label>
                                </div>
                                <div class="col-sm-9 col-lg-10 ps-sm-2 ps-md-4 position-relative my-3 py-0">
                                    {!! Form::textarea('message_body', old('message_body', $data['message_body'] ?? ''), [
                                        'class' => 'form-control ' . validationInputClass($errors->has('message_body')),
                                        'rows' => 8,
                                        'maxlength' => 65535,
                                        'placeholder' => 'Please enter the details of inquiry.',
                                    ]) !!}
                                    {{ validationMessage($errors->first('message_body')) }}
                                </div>
                            </div>
                            @if (env('ENABLE_RECAPTCHA', false))
                                <div class="row">
                                    <div class="col-sm-3 col-lg-2">
                                    </div>
                                    <div class="col-sm-9 col-lg-10 ps-5">
                                        <div id="recaptcha_element"></div>
                                        {{ validationMessage($errors->first('g-recaptcha-response')) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="contact-button d-flex flex-column align-items-center justify-content-center">
                            <button class="btn-contact rounded-pill mb-sm-5 mt-sm-5 mb-4 mt-3 border border-0 shadow"
                                type="submit"><span>{{ __('cms::contact.confirm') }}</span></button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </section>
        </div>
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3533.050527154616!2d85.32045321501488!3d27.684833182801405!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb19b79f8cee05%3A0x2aa02575b6be6f2e!2sMiracle%20Interface!5e0!3m2!1sen!2snp!4v1660295369851!5m2!1sen!2snp"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
@endsection('content')
@include('common::__partial.recaptcha')
