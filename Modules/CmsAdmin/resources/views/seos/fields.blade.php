<!-- Module Name Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('module_name')) }}">
    {!! Form::label('module_name', __('cmsadmin::models/seos.fields.module_name') . ':') !!}
    {!! Form::text('module_name', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('module_name')),
        'required',
        'maxlength' => 50,
    ]) !!}
    {{ validationMessage($errors->first('module_name')) }}
</div>

<!-- Code Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('code')) }}">
    {!! Form::label('code', __('cmsadmin::models/seos.fields.code') . ':') !!}
    {!! Form::text('code', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('code')),
        'required',
        'maxlength' => 255,
    ]) !!}
    {{ validationMessage($errors->first('code')) }}
</div>

<!-- Title Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('title')) }}">
    {!! Form::label('title', __('cmsadmin::models/seos.fields.title') . ':') !!}
    {!! Form::text('title', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('title')),
        'required',
        'rows' => 6,
        'maxlength' => 255,
    ]) !!}
    {{ validationMessage($errors->first('title')) }}
</div>

<!-- Title Locale Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('title_locale')) }}">
    {!! Form::label('title_locale', __('cmsadmin::models/seos.fields.title_locale') . ':') !!}
    {!! Form::text('title_locale', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('title_locale')),
        'required',
        'rows' => 6,
        'maxlength' => 255,
    ]) !!}
    {{ validationMessage($errors->first('title_locale')) }}
</div>

<!-- Keyword Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('keyword')) }}">
    {!! Form::label('keyword', __('cmsadmin::models/seos.fields.keyword') . ':') !!}
    {!! Form::textarea('keyword', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('keyword')),
        'required',
        'rows' => 6,
        'maxlength' => 255,
    ]) !!}
    {{ validationMessage($errors->first('keyword')) }}
</div>

<!-- Keyword Locale Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('keyword_locale')) }}">
    {!! Form::label('keyword_locale', __('cmsadmin::models/seos.fields.keyword_locale') . ':') !!}
    {!! Form::textarea('keyword_locale', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('keyword_locale')),
        'required',
        'rows' => 6,
        'maxlength' => 255,
    ]) !!}
    {{ validationMessage($errors->first('keyword_locale')) }}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('description')) }}">
    {!! Form::label('description', __('cmsadmin::models/seos.fields.description') . ':') !!}
    {!! Form::textarea('description', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('description')),
        'required',
        'rows' => 6,
        'maxlength' => 255,
    ]) !!}
    {{ validationMessage($errors->first('description')) }}
</div>

<!-- Description Locale Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('description_locale')) }}">
    {!! Form::label('description_locale', __('cmsadmin::models/seos.fields.description_locale') . ':') !!}
    {!! Form::textarea('description_locale', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('description_locale')),
        'required',
        'rows' => 6,
        'maxlength' => 255,
    ]) !!}
    {{ validationMessage($errors->first('description_locale')) }}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            $('[data-toggle="switch"]').bootstrapSwitch('state', $(this).prop('checked'));
        })
    </script>
@endpush
