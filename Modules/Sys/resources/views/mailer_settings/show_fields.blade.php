<!-- Default Admin Name -->
<div class="col-sm-12">
    {!! Form::label('default_admin_mail', __('sys::models/mailer_settings.default_admin_mail')) !!}
    <span class="semicolon mr-3">:</span>
    <p>
        {{ !empty(getSiteSettings('admin_email')) ? getSiteSettings('admin_email') : ADMIN_EMAIL }}
    </p>
</div>

<!-- Current Mailer type -->
<div class="col-sm-12">
    {!! Form::label('current_mailer_type', __('sys::models/mailer_settings.current_mailer_type')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ __('sys::models/mailer_settings.symfony') }}</p>
</div>

<!-- Smtp Host Field -->
<div class="col-sm-12">
    {!! Form::label('smtp_host', __('sys::models/mailer_settings.fields.smtp_host')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $mailerSetting->smtp_host }}</p>
</div>

<!-- Smtp Port Field -->
<div class="col-sm-12">
    {!! Form::label('smtp_port', __('sys::models/mailer_settings.fields.smtp_port')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $mailerSetting->smtp_port }}</p>
</div>

<!-- Smtp Username Field -->
<div class="col-sm-12">
    {!! Form::label('smtp_username', __('sys::models/mailer_settings.fields.smtp_username')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $mailerSetting->smtp_username }}</p>
</div>

<!-- Smtp Password Field -->
<div class="col-sm-12">
    {!! Form::label('smtp_password', __('sys::models/mailer_settings.fields.smtp_password')) !!}
    <span class="semicolon mr-3">:</span>
    <p>
        @php
            if (!empty($mailerSetting->smtp_password)) {
                try {
                    $decryptedPW = Crypt::decryptString($mailerSetting->smtp_password);
                    echo $decryptedPW ? str_repeat('*', strlen($decryptedPW)) : '';
                } catch (DecryptException $e) {
                    // Handle decryption error
                    echo 'Error: Unable to decrypt password';
                }
            }
        @endphp
    </p>
</div>

<!-- Ssl Verify Field -->
<div class="col-sm-12">
    {!! Form::label('ssl_verify', __('sys::models/mailer_settings.fields.ssl_verify')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{!! getSSLVerifyText($mailerSetting->ssl_verify) !!}</p>
</div>
@if (request()->route()->getName() == 'sys.mailerSettings.show' || request()->route()->getName() == 'sys.mailerSettings.index')
    <!-- Created At Field -->
    <div class="col-sm-12">
        {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $mailerSetting->created_at }}</p>
    </div>

    <!-- Created By Field -->
    <div class="col-sm-12">
        {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>
            {{ $mailerSetting->created_by ? getUserDataById($mailerSetting->created_by) : '' }}
        </p>
    </div>

    @if (!empty($mailerSetting->updated_at))
        <!-- Updated At Field -->
        <div class="col-sm-12">
            {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $mailerSetting->updated_at }}</p>
        </div>
    @endif

    @if (!empty($mailerSetting->updated_by))
        <!-- Updated By Field -->
        <div class="col-sm-12">
            {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($mailerSetting->updated_by) }}</p>
        </div>
    @endif
@endif
