<!-- Name Field -->
<div class="form-group col-md-6">
    <div class="bg-light mb-2 px-2 pt-2">{!! Form::label('name', __('sys::models/users.fields.name') . ':') !!}</div>
    <div class="clearfix w-100"></div>
    {!! Form::label('name', $user->name) !!}
</div>

<!-- Email Field -->
<div class="form-group col-md-6">
    <div class="bg-light mb-2 px-2 pt-2">{!! Form::label('email', __('sys::models/users.fields.email') . ':') !!}</div>
    <div class="clearfix w-100"></div>
    {!! Form::label('email', $user->email) !!}
</div>

@if (auth()->id() == $user->id)
    <!-- Current Password Field -->
    <div class="form-group col-md-6 required {{ validationClass($errors->has('password')) }}">
        {!! Form::label('password', __('sys::models/users.fields.password_current') . ':') !!}
        {!! Form::password('password', ['autocomplete' => 'off', 'class' => 'form-control']) !!}
        {{ validationMessage($errors->first('password')) }}
    </div>
@endif

<!-- New Password Field -->
<div class="form-group col-md-6 required {{ validationClass($errors->has('new_password')) }}">
    {!! Form::label('new_password', __('sys::models/users.fields.password_new') . ':') !!}
    {!! Form::password('new_password', ['autocomplete' => 'off', 'class' => 'form-control']) !!}
    {{ validationMessage($errors->first('new_password')) }}
</div>

<!-- Confirm Password Field -->
<div class="form-group col-md-6 required {{ validationClass($errors->has('new_password_confirmation')) }}">
    {!! Form::label('new_password_confirmation', __('sys::models/users.fields.password_confirm') . ':') !!}
    {!! Form::password('new_password_confirmation', ['autocomplete' => 'off', 'class' => 'form-control']) !!}
    {{ validationMessage($errors->first('new_password_confirmation')) }}
</div>
