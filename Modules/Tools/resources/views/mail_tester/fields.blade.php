<div class="col-sm-6">
    <div class="form-group required {{ validationClass($errors->has('subject')) }}">
        {!! Form::label('subject', __('tools::models/mail_testers.fields.subject') . ':') !!}
        {!! Form::text('subject', null, [
            'class' => 'form-control ' . validationInputClass($errors->has('subject')),
            'required',
            'maxlength' => 100,
        ]) !!}
        <p class="text-danger"></p>
    </div>

    <div class="form-group required {{ validationClass($errors->has('email')) }}">
        {!! Form::label('email', __('tools::models/mail_testers.fields.email') . ':') !!}
        {!! Form::text('email', null, [
            'class' => 'form-control ' . validationInputClass($errors->has('email')),
            'required',
            'maxlength' => 100,
        ]) !!}
        <p class="text-danger"></p>
    </div>

    <div class="form-group {{ validationClass($errors->has('cc_email')) }}">
        {!! Form::label('cc_email', __('tools::models/mail_testers.fields.cc_email') . ':') !!}
        {!! Form::select('cc_email[]', [], null, [
            'class' => 'form-control ' . validationInputClass($errors->has('cc_email')),
            'maxlength' => 255,
            'id' => 'cc_email',
            'multiple' => 'multiple',
        ]) !!}
        <p class="text-danger"></p>
    </div>

    <div class="form-group {{ validationClass($errors->has('bcc_email')) }}">
        {!! Form::label('bcc_email', __('tools::models/mail_testers.fields.bcc_email') . ':') !!}
        {!! Form::select('bcc_email[]', [], null, [
            'class' => 'form-control ' . validationInputClass($errors->has('bcc_email')),
            'maxlength' => 255,
            'id' => 'bcc_email',
            'multiple' => 'multiple',
        ]) !!}
        <p class="text-danger"></p>
    </div>

    <div class="form-group required {{ validationClass($errors->has('message')) }}">
        {!! Form::label('message', __('tools::models/mail_testers.fields.message') . ':') !!}
        {!! Form::textarea('message', null, [
            'class' => 'form-control ' . validationInputClass($errors->has('message')),
            'required',
            'maxlength' => 65535,
        ]) !!}
        <p class="text-danger"></p>
    </div>
</div>
<div class="col-sm-6">
    @include('sys::mailer_settings.show_fields')
</div>
