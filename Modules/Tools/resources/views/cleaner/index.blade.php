@extends('tools::layouts.master')

@section('content')
    {{ Breadcrumbs::render('cleaner') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-6">
            <div class="card-header">
                <h4>{{ __('tools::common.app_cleaner') }}</h4>
            </div>

            <div class="card-body px-0">

                <div class="d-flex">
                    @if (checkToolsPermission('cleaner.cache'))
                        <a id= "clear_cache" class="btn btn-success btn-sm no-corner mr-2"
                            href="{!! route('tools.cleaner.cache') !!}">{{ __('tools::cleaners.clear_cache') }}</a>
                    @endif
                    @if (checkToolsPermission('cleaner.config'))
                        <a id= "clear_config" class="btn btn-success btn-sm no-corner mr-2"
                            href="{!! route('tools.cleaner.config') !!}">{{ __('tools::cleaners.clear_config') }}</a>
                    @endif
                    @if (checkToolsPermission('cleaner.views'))
                        <a id= "clear_views" class="btn btn-success btn-sm no-corner mr-2"
                            href="{!! route('tools.cleaner.views') !!}">{{ __('tools::cleaners.clear_views') }}</a>
                    @endif
                    @if (checkToolsPermission('cleaner.route'))
                        <a id= "clear_route" class="btn btn-warning btn-sm no-corner mr-2"
                            href="{!! route('tools.cleaner.route') !!}">{{ __('tools::cleaners.clear_route') }}</a>
                    @endif
                    @if (checkToolsPermission('cleaner.optimize'))
                        <a id= "clear_optimize" class="btn btn-danger btn-sm no-corner mr-2"
                            href="{!! route('tools.cleaner.optimize') !!}">{{ __('tools::cleaners.clear_optimize') }}</a>
                    @endif
                    @if (checkToolsPermission('cleaner.permissionCache'))
                        <a id= "clear_permission_cache" class="btn btn-warning btn-sm no-corner mr-2"
                            href="{!! route('tools.cleaner.permissionCache') !!}">{{ __('tools::cleaners.clear_permission_cache') }}</a>
                    @endif
                </div>

                <div class="row alert-msg">
                    <div class="col-sm-12">@include('flash::message')</div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('tools::__partial.cleaner_js')
