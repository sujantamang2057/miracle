@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('user_detail', $user) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('sys::models/users.singular') }} {{ __('common::crud.detail') }} -
                        {{ __('common::crud.trashed') }}</h1>
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
                    @if (checkSysPermission('users.trashList'))
                        {!! renderLinkButton(__('common::crud.back'), route('sys.users.trashList'), 'chevron-circle-left', 'dark', '') !!}
                    @endif
                    @if (checkSysPermissionList(['users.create', 'users.store']))
                        {!! renderLinkButton(__('common::crud.create'), route('sys.users.create'), 'plus', 'success', '') !!}
                    @endif
                    @if (checkSysPermission('users.restore'))
                        {!! renderLinkButtonWithId(
                            __('common::crud.restore'),
                            $user->id,
                            route('sys.users.restore', ['id' => $user->id]),
                            'recycle',
                            'warning btn-trash-restore',
                            '',
                        ) !!}
                    @endif
                    @if (checkSysPermission('users.permanentDestroy'))
                        {!! renderDeleteBtn('sys.users.permanentDestroy', $user->id, $showDeleteBtn, null, 'trash', 'permanent') !!}
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.trash_restore')
@include('common::__partial.swal_datatable')
