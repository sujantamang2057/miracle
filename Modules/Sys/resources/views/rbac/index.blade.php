@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('rbac') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('sys::models/roles.text.rbac') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @include('flash::message')
        <div class="card mb-7">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>{{ __('sys::models/roles.singular') }}</h4>
                        @isset($roleDataTable)
                            {{ $roleDataTable->table(['id' => 'role-data-table', 'width' => '100%', 'class' => 'table table-striped table-bordered']) }}
                        @endisset
                    </div>
                    <div class="col-md-6">
                        <h4>{{ __('sys::models/permissions.singular') }}</h4>
                        @isset($permissionDataTable)
                            {{ $permissionDataTable->table(['id' => 'permission-data-table', 'width' => '100%', 'class' => 'table table-striped table-bordered']) }}
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('common::__partial.swal_datatable')

@push('page_scripts')
    @isset($roleDataTable)
        {{ $roleDataTable->scripts() }}
    @endisset
    @isset($permissionDataTable)
        {{ $permissionDataTable->scripts() }}
    @endisset
@endpush
