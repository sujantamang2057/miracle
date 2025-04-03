<!-- Parent Id Field -->
<div class="col-sm-12">
    {!! Form::label('parent_id', __('cmsadmin::models/menus.fields.parent_id')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $menu->parent->title ?? '' }}</p>
</div>

<!-- Title Field -->
<div class="col-sm-12">
    {!! Form::label('title', __('cmsadmin::models/menus.fields.title')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $menu->title }}</p>
</div>

<!-- Slug Field -->
<div class="col-sm-12">
    {!! Form::label('slug', __('cmsadmin::models/menus.fields.slug')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $menu->slug }}</p>
</div>

<!-- Url Field -->
<div class="col-sm-12">
    {!! Form::label('url', __('cmsadmin::models/menus.fields.url')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $menu->url }}</p>
</div>

<!-- Url Type Field -->
<div class="col-sm-12">
    {!! Form::label('url_type', __('cmsadmin::models/menus.fields.url_type')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! getUrlTypeText($menu->url_type) !!}</p>
</div>

<!-- Url Target Field -->
<div class="col-sm-12">
    {!! Form::label('url_target', __('cmsadmin::models/menus.fields.url_target')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! getUrlTargetText($menu->url_target) !!}</p>
</div>

<!-- Css Class Field -->
<div class="col-sm-12">
    {!! Form::label('css_class', __('cmsadmin::models/menus.fields.css_class')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $menu->css_class }}</p>
</div>

<!-- Tooltip Field -->
<div class="col-sm-12">
    {!! Form::label('tooltip', __('cmsadmin::models/menus.fields.tooltip')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $menu->tooltip }}</p>
</div>

<!-- Active Field -->
<div class="col-sm-12">
    {!! Form::label('active', __('cmsadmin::models/menus.fields.active')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getactiveText($menu->active) }}</p>
    @else
        <p>
            {!! manageRenderBsSwitchGrid('menus.toggleActive', 'active', $menu->menu_id, $menu->active, 'cmsadmin.menus.toggleActive') !!}
        </p>
    @endif
</div>

<!-- Reserved Field -->
<div class="col-sm-12">
    {!! Form::label('reserved', __('common::crud.fields.reserved')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getReservedText($menu->reserved) }}</p>
    @else
        <p>
            {!! manageRenderBsSwitchGrid('menus.toggleReserved', 'reserved', $menu->menu_id, $menu->reserved, 'cmsadmin.menus.toggleReserved') !!}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $menu->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ !empty($menu->created_by) ? getUserDataById($menu->created_by) : '' }}</p>
</div>

@if ($menu->updated_at)
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $menu->updated_at }}</p>
    </div>
@endif

@if ($menu->updated_by)
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataById($menu->updated_by) }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($menu->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $menu->deleted_at }}</p>
        </div>
    @endif

    @if ($menu->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($menu->deleted_by) }}</p>
        </div>
    @endif
@endif
