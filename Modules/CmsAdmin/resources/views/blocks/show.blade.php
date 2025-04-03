@extends('cmsadmin::layouts.master')

@section('content')
    {{ Breadcrumbs::render('block_detail', $block) }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('cmsadmin::models/blocks.singular') }} {{ __('common::crud.detail') }}</h1>
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
                {!! renderLinkButton(__('common::crud.back'), route('cmsadmin.blocks.index'), 'chevron-circle-left', 'warning', '') !!}
                @if (checkCmsAdminPermissionList(['blocks.create', 'blocks.store']))
                    {!! renderLinkButton(__('common::crud.create'), route('cmsadmin.blocks.create'), 'plus', 'success', '') !!}
                @endif
                @if (checkCmsAdminPermissionList(['blocks.edit', 'blocks.update']))
                    {!! renderLinkButton(__('common::crud.update'), route('cmsadmin.blocks.edit', [$block->block_id]), 'edit', 'primary', '') !!}
                @endif
                @if (checkCmsAdminPermission('blocks.regenerate'))
                    {!! renderLinkButtonWithId(
                        __('common::crud.regenerate'),
                        $block->block_id,
                        route('cmsadmin.blocks.regenerate'),
                        'retweet',
                        'info btn-regenerate',
                        '',
                    ) !!}
                @endif
                @if (checkCmsAdminPermission('blocks.destroy'))
                    {!! renderDeleteBtn('cmsadmin.blocks.destroy', $block->block_id, $block->reserved == 2) !!}
                @endif
            </div>
        </div>
    </div>
@endsection
@include('common::__partial.show_alert')
@include('common::__partial.regenerate')
@include('common::__partial.swal_datatable')
@include('common::__partial.publish_toggle')
@include('common::__partial.reserved_toggle')
