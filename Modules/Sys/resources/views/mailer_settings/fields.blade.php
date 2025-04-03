<!-- Default Admin Name -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('smtp_host')) }}">
    {!! Form::label('default_admin_mail', __('sys::models/mailer_settings.default_admin_mail') . ':') !!}
    {!! Form::text('default_admin_mail', null, [
        'class' => 'form-control ',
        'placeholder' => !empty(getSiteSettings('admin_email')) ? getSiteSettings('admin_email') : ADMIN_EMAIL,
        'readonly' => 'readonly',
        'maxlength' => 50,
    ]) !!}
</div>

<!-- Current Mailer type -->
<div class="form-group col-sm-6">
    {!! Form::label('current_mailer_type', __('sys::models/mailer_settings.current_mailer_type') . ':') !!}
    {!! Form::text('current_mailer_type', null, [
        'class' => 'form-control ',
        'placeholder' => __('sys::models/mailer_settings.symfony'),
        'readonly' => 'readonly',
        'maxlength' => 50,
    ]) !!}
</div>

<!-- Smtp Host Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('smtp_host')) }}">
    {!! Form::label('smtp_host', __('sys::models/mailer_settings.fields.smtp_host') . ':') !!}
    {!! Form::text('smtp_host', null, ['class' => 'form-control', 'maxlength' => 200, 'maxlength' => 200]) !!}
    {{ validationMessage($errors->first('smtp_host')) }}
</div>

<!-- Smtp Port Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('smtp_port')) }}">
    {!! Form::label('smtp_port', __('sys::models/mailer_settings.fields.smtp_port') . ':') !!}
    {!! Form::text('smtp_port', null, ['class' => 'form-control', 'maxlength' => 200, 'maxlength' => 200]) !!}
    {{ validationMessage($errors->first('smtp_port')) }}
</div>

<!-- Smtp Username Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('smtp_username')) }}">
    {!! Form::label('smtp_username', __('sys::models/mailer_settings.fields.smtp_username') . ':') !!}
    {!! Form::text('smtp_username', null, ['class' => 'form-control', 'maxlength' => 200, 'maxlength' => 200]) !!}
    {{ validationMessage($errors->first('smtp_username')) }}
</div>

<!-- Smtp Password Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('smtp_password')) }}">
    {!! Form::label('smtp_password', __('sys::models/mailer_settings.fields.smtp_password') . ':') !!}
    <input type="password" name="smtp_password" value="{{ $smtp_password }}" class="form-control @error('smtp_password') is-invalid @enderror" />
    {{ validationMessage($errors->first('smtp_password')) }}
</div>

<!-- SSL Verify Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('ssl_verify')) }}">
    {!! Form::label('ssl_verify', __('sys::models/mailer_settings.fields.ssl_verify') . ':') !!}
    {!! Form::hidden('ssl_verify', 2) !!}
    {!! renderBootstrapSwitchTrueFalse('ssl_verify', $id, $ssl_verify, old('ssl_verify')) !!}
    {{ validationMessage($errors->first('ssl_verify')) }}
</div>
<div class="form-group col-sm-12">
    @if (getActionName() == 'edit')
        {!! renderSubmitButton(__('common::crud.btn.update'), 'primary', '') !!}
    @else
        {!! renderSubmitButton(__('common::crud.btn.create'), 'success', '') !!}
    @endif
    {!! renderLinkButton(__('common::crud.btn.cancel'), route('sys.mailerSettings.index'), 'times', 'warning', '') !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            $('[data-toggle="switch"]').bootstrapSwitch(
                'state',
                $(this).prop('checked')
            );
        });
    </script>
@endpush
