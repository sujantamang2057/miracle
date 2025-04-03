<div class = "col-sm-6">
    <div class="form-group required{{ validationClass($errors->has('directive')) }}">
        {!! Form::label('directive', __('cmsadmin::models/csp_headers.fields.directive') . ':') !!}
        {!! Form::select('directive[]', $selectDirectives, null, [
            'class' => 'form-control ' . validationInputClass($errors->has('directive')),
            'maxlength' => 255,
            'id' => 'directive',
        ]) !!}
        <p class="text-danger"></p>
    </div>
</div>

<div class = "col-sm-6">
    <div class="form-group {{ validationClass($errors->has('schema')) }}">
        {!! Form::label('schema', __('cmsadmin::models/csp_headers.fields.schema') . ':') !!}
        {!! Form::select('schema[]', $selectSchemas, $alreadySelectedSchema, [
            'class' => 'form-control ' . validationInputClass($errors->has('schema')),
            'maxlength' => 255,
            'id' => 'schema',
            'multiple' => 'multiple',
        ]) !!}
        <p class="text-danger"></p>
    </div>

</div>

<div class="form-group col-sm-12">
    <div class="form-group required{{ validationClass($errors->has('keyword')) }}">
        {!! Form::label('keyword', __('cmsadmin::models/csp_headers.fields.keyword') . ':') !!}
        {!! Form::select('keyword[]', $selectKeywords, $alreadySelectedKeyword, [
            'class' => 'form-control ' . validationInputClass($errors->has('keyword')),
            'maxlength' => 255,
            'id' => 'keyword',
            'multiple' => 'multiple',
        ]) !!}

    </div>
</div>

<div class = "col-sm-12">
    <div class="form-group {{ validationClass($errors->has('value')) }}">
        {!! Form::label('value', __('cmsadmin::models/csp_headers.fields.value') . ':') !!}
        {!! Form::select('value[]', $alreadySelectedValue, $alreadySelectedValue, [
            'class' => 'form-control ' . validationInputClass($errors->has('value')),
            'maxlength' => 255,
            'id' => 'value',
            'multiple' => 'multiple',
            'size' => 8,
        ]) !!}
        <p class="text-danger"></p>
    </div>
</div>
<div class = "col-sm-6">
    <div class="form-group {{ validationClass($errors->has('publish')) }}">
        <div class="form-check mr-3 pl-0">
            {!! Form::label('publish', __('common::crud.fields.publish') . ':') !!}
            @if (checkCmsAdminPermission('cspHeaders.togglePublish'))
                {!! Form::hidden('publish', 2) !!}
                {!! renderBootstrapSwitchPublish('publish', $id, $publish, old('publish')) !!}
                {{ validationMessage($errors->first('publish')) }}
            @else
                {{ getPublishText(2) }}
            @endif
        </div>
    </div>
</div>
