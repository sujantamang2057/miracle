@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('sns_edit', $sns) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('common::crud.edit') }} {{ __('sys::models/sns.singular') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-7">
            {!! Form::model($sns, [
                'route' => ['sys.sns.update', $sns->sns_id],
                'method' => 'patch',
            ]) !!}
            <div class="card-body">
                <div class="row">
                    @include('sys::sns.fields')
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
