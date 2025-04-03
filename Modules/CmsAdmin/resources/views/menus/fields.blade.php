<div class="row">
    <!-- Title Field -->
    <div class="form-group col-md-6 required {{ validationClass($errors->has('title')) }}">
        {!! Form::label('title', __('cmsadmin::models/menus.fields.title') . ':') !!}
        {!! Form::text('title', null, [
            'class' => 'form-control ' . validationInputClass($errors->has('title')),
            'maxlength' => 191,
        ]) !!}
        {{ validationMessage($errors->first('title')) }}
    </div>

    <!-- Parent Id Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('parent_id', __('cmsadmin::models/menus.fields.parent_id') . ':') !!}
        {!! Form::select('parent_id', $parentMenuList, null, [
            'class' => 'form-control ' . validationInputClass($errors->has('parent_id')),
        ]) !!}
        {{ validationMessage($errors->first('parent_id')) }}
    </div>

    <div class="col-md-6">
        <!-- Slug Field -->
        <div class="form-group required {{ validationClass($errors->has('slug')) }}">
            {!! Form::label('slug', __('cmsadmin::models/menus.fields.slug') . ':') !!}
            {!! Form::text('slug', null, [
                'class' => 'form-control ' . validationInputClass($errors->has('slug')),
                'placeholder' => __('common::crud.messages.auto_generate_slug'),
                'maxlength' => 191,
            ]) !!}
            {{ validationMessage($errors->first('slug')) }}
        </div>

        <!-- Css Class Field -->
        <div class="form-group {{ validationClass($errors->has('css_class')) }}">
            {!! Form::label('css_class', __('cmsadmin::models/menus.fields.css_class') . ':') !!}
            {!! Form::text('css_class', null, ['class' => 'form-control', 'maxlength' => 255]) !!}
        </div>

        <!-- Tooltip Field -->
        <div class="form-group {{ validationClass($errors->has('tooltip')) }}">
            {!! Form::label('tooltip', __('cmsadmin::models/menus.fields.tooltip') . ':') !!}
            {!! Form::text('tooltip', null, ['class' => 'form-control', 'maxlength' => 255]) !!}
        </div>
    </div>

    <div class="col-sm-6">
        <!-- Url Type Field -->
        <div class="form-group url_type {{ validationClass($errors->has('url_type')) }}">
            {!! Form::label('url_type', __('cmsadmin::models/menus.fields.url_type') . ':') !!}
            {!! Form::hidden('url_type', 2) !!}
            {!! renderBootstrapSwitchUrlType('url_type', $id, $url_type, old('url_type')) !!}
            {{ validationMessage($errors->first('url_type')) }}
        </div>

        <!-- Url Field -->
        <div class="form-group required {{ validationClass($errors->has('url')) }}">
            {!! Form::label('url', __('cmsadmin::models/menus.fields.url') . ':') !!}
            {!! Form::text('url', null, [
                'class' => 'form-control ' . validationInputClass($errors->has('url')),
                'maxlength' => 255,
            ]) !!}
            {{ validationMessage($errors->first('url')) }}
        </div>

        <!-- Url Target Field -->
        <div class="form-group url_target {{ validationClass($errors->has('url_target')) }}">
            {!! Form::label('url_target', __('cmsadmin::models/menus.fields.url_target') . ':') !!}
            {!! Form::hidden('url_target', 2) !!}
            {!! renderBootstrapSwitchUrlTarget('url_target', $id, $url_target, old('url_target')) !!}
            {{ validationMessage($errors->first('url_target')) }}
        </div>
    </div>
    <div class="col-sm-6">
        <!-- Active Field -->
        <div class="form-group {{ validationClass($errors->has('active')) }}">
            <div class="form-check mr-3 pl-0">
                {!! Form::label('active', __('cmsadmin::models/menus.fields.active') . ':') !!}
                @if (checkCmsAdminPermission('menus.toggleActive'))
                    {!! Form::hidden('active', 2) !!}
                    {!! renderBootstrapSwitchActive('active', $id, $active, old('active')) !!}
                    {{ validationMessage($errors->first('active')) }}
                @else
                    {{ getActiveText(2) }}
                @endif
            </div>
        </div>

        <!-- Reserved Field -->
        <div class="form-group {{ validationClass($errors->has('reserved')) }}">
            <div class="form-check mr-3 p-0">
                {!! Form::label('reserved', __('common::crud.fields.reserved') . ':') !!}
                @if (checkCmsAdminPermission('menus.toggleReserved'))
                    {!! Form::hidden('reserved', 2) !!}
                    {!! renderBootstrapSwitchReserved('reserved', $id, $reserved, old('reserved')) !!}
                    {{ validationMessage($errors->first('reserved')) }}
                @else
                    {{ getReservedText(2) }}
                @endif
            </div>
        </div>
        @if (getActionName() == 'edit')
            {!! renderSubmitButton(__('common::crud.btn.update'), 'primary', '') !!}
        @else
            {!! renderSubmitButton(__('common::crud.btn.create'), 'success', '') !!}
        @endif
        {!! renderLinkButton(__('common::crud.btn.cancel'), route('cmsadmin.menus.index'), 'times', 'warning', '') !!}
    </div>
</div>

@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            $('[data-toggle="switch"]').bootstrapSwitch('state', $(this).prop('checked'));
        })
    </script>
@endpush
