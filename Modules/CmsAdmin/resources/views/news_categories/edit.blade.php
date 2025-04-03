@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('news_category_edit', $newsCategory) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('common::crud.edit') }} {{ __('cmsadmin::models/news_categories.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            {!! Form::model($newsCategory, [
                'route' => ['cmsadmin.newsCategories.update', $newsCategory->category_id],
                'method' => 'patch',
            ]) !!}
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::news_categories.fields')
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
