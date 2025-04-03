<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', __('sys::models/email_templates.fields.name')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $emailTemplate->name }}</p>
</div>

<!-- Mail Code Field -->
<div class="col-sm-12">
    {!! Form::label('mail_code', __('sys::models/email_templates.fields.mail_code')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $emailTemplate->mail_code }}</p>
</div>

<!-- Subject Field -->
<div class="col-sm-12">
    {!! Form::label('subject', __('sys::models/email_templates.fields.subject')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $emailTemplate->subject }}</p>
</div>

<!-- Variables Field -->
<div class="col-sm-12">
    {!! Form::label('variables', __('sys::models/email_templates.fields.variables')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $emailTemplate->variables }}</p>
</div>

<!-- Contents Field -->
<div class="col-sm-12">
    {!! Form::label('contents', __('sys::models/email_templates.fields.contents')) !!}
    <span class="semicolon mr-3">:</span>
    <p>
        <pre>{{ $emailTemplate->contents }}</pre>
    </p>
</div>

<!-- Publish Field -->
<div class="col-sm-12">
    {!! Form::label('publish', __('common::crud.fields.publish')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getPublishText($emailTemplate->publish) }}</p>
    @else
        <p>{!! manageRenderBsSwitchGrid(
            'emailTemplates.togglePublish',
            'publish',
            $emailTemplate->template_id,
            $emailTemplate->publish,
            'sys.emailTemplates.togglePublish',
        ) !!}
        </p>
    @endif
</div>

<!-- Reserved Field -->
<div class="col-sm-12">
    {!! Form::label('reserved', __('common::crud.fields.reserved')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getReservedText($emailTemplate->reserved) }}</p>
    @else
        <p>{!! manageRenderBsSwitchGrid(
            'emailTemplates.toggleReserved',
            'reserved',
            $emailTemplate->template_id,
            $emailTemplate->reserved,
            'sys.emailTemplates.toggleReserved',
        ) !!}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $emailTemplate->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $emailTemplate->created_by ? getUserDataById($emailTemplate->created_by) : '' }}</p>
</div>

@if ($emailTemplate->updated_at)
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $emailTemplate->updated_at }}</p>
    </div>
@endif

@if ($emailTemplate->updated_by)
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataById($emailTemplate->updated_by) }}</p>
    </div>
@endif

@if ($mode == 'trash-restore')
    @if ($emailTemplate->deleted_at)
        <!-- Deleted At Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_at', __('common::crud.fields.deleted_at')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ $emailTemplate->deleted_at }}</p>
        </div>
    @endif

    @if ($emailTemplate->deleted_by)
        <!-- Deleted By Field -->
        <div class="col-sm-12">
            {!! Form::label('deleted_by', __('common::crud.fields.deleted_by')) !!}
            <span class="semicolon mr-3">:</span>
            <p>{{ getUserDataById($emailTemplate->deleted_by) }}</p>
        </div>
    @endif
@endif
