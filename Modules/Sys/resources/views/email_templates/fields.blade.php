<!-- Name Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('name')) }}">
    {!! Form::label('name', __('sys::models/email_templates.fields.name') . ':') !!}
    {!! Form::text('name', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('name')),
        'maxlength' => 50,
    ]) !!}
    {{ validationMessage($errors->first('name')) }}
</div>

<!-- Mail Code Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('mail_code')) }}">
    {!! Form::label('mail_code', __('sys::models/email_templates.fields.mail_code') . ':') !!}
    {!! Form::text('mail_code', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('mail_code')),
        'placeholder' => 'MAIL-CODE-1',
        'maxlength' => 15,
    ]) !!}
    {{ validationMessage($errors->first('mail_code')) }}
</div>

<!-- Subject Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('subject')) }}">
    {!! Form::label('subject', __('sys::models/email_templates.fields.subject') . ':') !!}
    {!! Form::text('subject', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('subject')),
        'maxlength' => 150,
    ]) !!}
    {{ validationMessage($errors->first('subject')) }}
</div>

<!-- Variables Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('variables')) }}">
    {!! Form::label('variables', __('sys::models/email_templates.fields.variables') . ':') !!}
    {!! Form::select('variables[]', [], null, [
        'class' => 'form-control ' . validationInputClass($errors->has('variables')),
        'maxlength' => 255,
        'id' => 'variables',
        'multiple' => 'multiple',
    ]) !!}
    {{ validationMessage($errors->first('variables')) }}
</div>

<!-- Contents Field -->
<div class="form-group col-sm-12 col-lg-12 required {{ validationClass($errors->has('contents')) }}">
    {!! Form::label('contents', __('sys::models/email_templates.fields.contents') . ':') !!}
    {!! Form::textarea('contents', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('contents')),
        'maxlength' => 65535,
    ]) !!}
    {{ validationMessage($errors->first('contents')) }}
</div>

@include('common::__partial.select2-scripts')
@push('page_scripts')
    <script>
        $(function() {
            $('[data-toggle="switch"]').bootstrapSwitch('state', $(this).prop('checked'));
            var variables = JSON.parse({!! json_encode($variables) !!});
            initializeSelect2('#variables', variables, true, 25);
        });
    </script>
@endpush
