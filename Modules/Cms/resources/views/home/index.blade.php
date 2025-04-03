@extends('cms::layouts.master')

@section('content_header')
    <section class="sc-banner position-relative">
        <div class="banner-slider">
            <x-blocks.banner :banners=$banners />
        </div>
    </section>
@endsection

@section('content')
    <div class="top-page">
        <x-blocks.about />
        <x-blocks.imagealbum :albums=$albums />
        <x-blocks.post :posts=$posts />
        <x-blocks.news :news=$news />
        <x-blocks.blogs :blogs=$blogs />
        <x-blocks.testimonials :testimonials=$testimonials />
    </div>
@endsection
