@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('block_trash_detail', $block) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/blocks.singular') }} {{ __('common::crud.detail') }} -
                        {{ __('common::crud.trashed') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content detail-content px-3">
        @include('flash::message')
        <div class="card card-width-lg">
            <div class="card-body">
                <div class="row">
                    @include('cmsadmin::blocks.show_fields')
                </div>
            </div>
            <div class="card-footer d-flex column-gap-5">
                @if (checkCmsAdminPermission('blocks.trashList'))
                    {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.blocks.trashList'), 'chevron-circle-left', 'dark', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['blocks.create', 'blocks.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.blocks.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermission('blocks.restore'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.restore'),
                        $block->block_id,
                        route('cmsadmin.blocks.restore', ['id' => $block->block_id]),
                        'recycle',
                        'warning btn-trash-restore',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('blocks.permanentDestroy'))
                    {!! renderDeleteBtn('cmsadmin.blocks.permanentDestroy', $block->block_id, $block->reserved == 2, null, 'trash', 'permanent') !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.trash_restore')
@include('common::__partial.swal_datatable')
