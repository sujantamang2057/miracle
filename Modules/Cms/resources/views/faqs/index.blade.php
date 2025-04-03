@extends('cms::layouts.master')

@section('content_header')
    <section class="sc-breadcrumb">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: '>'" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="index.php">{{ __('cms::general.home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('cms::general.faq') }}</li>
                </ol>
            </nav>
        </div>
    </section>
@endsection

@section('content')
    <div class="faq-page">
        <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
            <div class="heading d-flex align-items-end justify-content-between mb-sm-5 pb-sm-4 mb-3 pb-1">
                <div class="">
                    <div class="sc-title text-uppercase fn-raleway">{{ __('cms::general.faq') }}</div>
                    <p class="sc-subtitle fw-bold mb-0">
                        {{ __('cms::general.questions') }}
                    </p>
                </div>
            </div>
        </div>
        <section class="sc-contact-page py-sm-5 py-3">
            @if ($faqCategorylist && $faqCategorylist->count())
                <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
                    @foreach ($faqCategorylist as $key => $faqCat)
                        <div class="contact-box {{ $key % 2 == 0 ? 'mb-4' : 'mb-5' }}">
                            <h4 class="fw-bold mb-3">{{ $faqCat->faq_cat_name }}</h4>
                            <div class="accordion" id="mi-accordion">
                                @php
                                    $faqList = $faqCat->activeFaq()->get();
                                @endphp
                                @if ($faqList?->count())
                                    @foreach ($faqList as $index => $faq)
                                        <div class="accordion-item">
                                            <h5 class="accordion-header d-flex align-items-center">
                                                <button class="fw-semibold accordion-button collapsed mb-0 py-4" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#{{ $faq->faq_cat_id . '-' . $index }}" aria-expanded="false"
                                                    aria-controls="{{ $faq->faq_cat_id . '-' . $index }}">
                                                    {{ $faq->question }}
                                                </button>
                                            </h5>
                                            <div id="{{ $faq->faq_cat_id . '-' . $index }}" class="accordion-collapse collapse"
                                                data-bs-parent="#mi-accordion">
                                                <div class="accordion-body">
                                                    <p>{!! nl2br($faq->answer ?? '') !!}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="not-found-class">
                    <div class="fs-1 fw-bold color-theme text-uppercase text-center">{{ __('common::messages.not_found') }}
                    </div>
                    <div class="fs-5 text-center">
                        <a href="{{ route('cms.home.index') }}" class="btn btn-success bg-color-green rounded-full">
                            <span>{{ __('cms::general.home') }}</span>
                        </a>
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection
