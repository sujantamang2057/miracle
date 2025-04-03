<div class="row">
    <div class="col-sm-4">
        <div class="form-group required {{ validationClass($errors->has('module_name')) }}">
            {!! Form::label('module_name', __('tools::image_regenerators.fields.module_name') . ':') !!}
            {!! Form::select('module_name', $modules, null, [
                'class' => 'form-control ' . validationInputClass($errors->has('module_name')),
                'id' => 'module_name',
                'onchange' => 'getImageColumn(event)',
            ]) !!}
            <p class="text-danger"></p>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group required {{ validationClass($errors->has('image_name')) }}">
            {!! Form::label('image_name', __('tools::image_regenerators.fields.image_name') . ':') !!}
            {!! Form::select('image_name', ['' => __('common::crud.text.select_any')], null, [
                'class' => 'form-control ' . validationInputClass($errors->has('image_name')),
                'id' => 'image_name',
                'onchange' => 'resetResponse(event, "#imageRegeneratorSubmitBtn")',
            ]) !!}
            <p class="text-danger"></p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group required {{ validationClass($errors->has('limit')) }}">
            {!! Form::label('limit', __('tools::image_regenerators.fields.limit') . ':') !!}
            {!! Form::select('limit', ['5' => '5', '10' => '10', '15' => '15', '20' => '20', '25' => '25'], null, [
                'class' => 'form-control ' . validationInputClass($errors->has('limit')),
                'id' => 'limit',
                'onchange' => 'resetResponse(event, "#imageRegeneratorSubmitBtn")',
            ]) !!}
            <p class="text-danger"></p>
        </div>
        @if (checkToolsPermission('imageRegenerator.regenerate'))
            <button type="button" class="btn btn-success lime" onclick="javascript:regenerate(event)"
                id="imageRegeneratorSubmitBtn">{{ __('common::crud.submit') }}</button>
        @endif
        @include('tools::__partial.reset')
    </div>
</div>
