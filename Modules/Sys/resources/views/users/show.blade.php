@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('user_detail', $user) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('sys::models/users.singular') }} {{ __('common::crud.detail') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('sys::users.show_fields')
                </div>
                <div class="d-flex column-gap-5 mt-3">
                    @php
                        $showDeleteBtn = $user->id != 1 && $user->id != auth()->user()->id;
                    @endphp
                    {!! renderLinkButton(__('common::crud.back'), route('sys.users.index'), 'chevron-circle-left', 'warning', '') !!}
                    @if (checkSysPermissionList(['users.create', 'users.store']))
                        {!! renderLinkButton(__('common::crud.create'), route('sys.users.create'), 'plus', 'success', '') !!}
                    @endif
                    @if (checkSysPermissionList(['users.edit', 'users.update']))
                        {!! renderLinkButton(__('common::crud.update'), route('sys.users.edit', [$user->id]), 'edit', 'primary', '') !!}
                    @endif
                    @if (checkSysPermission('users.changePassword'))
                        {!! $showDeleteBtn || (auth()->user()->id = 1)
                            ? renderLinkButton(__('common::crud.change_password'), route('sys.users.changePassword', [$user->id]), 'lock', 'dark', '')
                            : '' !!}
                    @endif
                    @if (checkSysPermission('users.changePassword'))
                        {!! renderDeleteBtn('sys.users.destroy', $user->id, $showDeleteBtn) !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.show_alert')
@if ($showDeleteBtn)
    @include('common::__partial.swal_datatable')
@endif
@include('common::__partial.active_toggle')
@include('common::__partial.remove_image_js')
