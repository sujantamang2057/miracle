@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('news_category_create') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('common::crud.btn.create') }} {{ __('cmsadmin::models/news_categories.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            {!! Form::open(['route' => 'cmsadmin.newsCategories.store']) !!}
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::news_categories.fields')
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
