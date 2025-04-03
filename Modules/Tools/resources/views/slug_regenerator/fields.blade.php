<div class="col-sm-4">
    <div class="form-group required {{ validationClass($errors->has('module_name')) }}">
        {!! Form::label('module_name', __('tools::slug_regenerators.fields.module_name') . ':') !!}
        {!! Form::select('module_name', $modules, null, [
            'class' => 'form-control ' . validationInputClass($errors->has('module_name')),
            'id' => 'module_name',
            'onchange' => 'resetResponse(event, "#slugRegeneratorSubmitBtn")',
        ]) !!}
        <p class="text-danger"></p>
    </div>
</div>
<div class="col-sm-4">
    <div class="form-group required {{ validationClass($errors->has('limit')) }}">
        {!! Form::label('limit', __('tools::slug_regenerators.fields.limit') . ':') !!}
        {!! Form::select('limit', ['5' => '5', '10' => '10', '15' => '15', '20' => '20', '25' => '25'], null, [
            'class' => 'form-control ' . validationInputClass($errors->has('limit')),
            'id' => 'limit',
            'onchange' => 'resetResponse(event)',
        ]) !!}
        <p class="text-danger"></p>
    </div>
</div>
<div class="col-sm-12">
    @if (checkToolsPermission('slugRegenerator.regenerate'))
        <button type="button" class="btn btn-success lime" onclick="javascript:regenerate(event)"
            id="slugRegeneratorSubmitBtn">{{ __('common::crud.submit') }}</button>
    @endif
    @include('tools::__partial.reset')
</div>
