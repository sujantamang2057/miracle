<div style="width: fit-content;">
    <div style="padding: 25px;">

        <span><strong>{!! Form::label('from_name', __('cmsadmin::models/contacts.fields.from_name') . ':') !!}</strong></span>
        <br><span>{{ $contact->from_name }}</span>

        <br><br><span><strong>{!! Form::label('from_email', __('cmsadmin::models/contacts.fields.from_email') . ':') !!}</strong></span>
        <br><span>{{ $contact->from_email }}</span>

        <br><br>
        <span><strong>{!! Form::label('subject', __('cmsadmin::models/contacts.fields.subject') . ':') !!}</strong></span>
        <br><span>{{ $contact->subject }}</span>

        <br><br>
        <span><strong>{!! Form::label('phone_no', __('cmsadmin::models/contacts.fields.phone_no') . ':') !!}</strong></span>
        @php
            $unserializedExtraDetails = !empty($contact->extra_details) ? unserialize($contact->extra_details) : '';
            $code = isset($unserializedExtraDetails['code']) ? $unserializedExtraDetails['code'] : '';
            $phoneNo = isset($unserializedExtraDetails['phone_no']) ? $unserializedExtraDetails['phone_no'] : '';
        @endphp
        <br><span>{{ $code . ' ' . $phoneNo }}</span>

        <br><br><span><strong>{!! Form::label('message_body', __('cmsadmin::models/contacts.fields.message_body') . ':') !!}</strong></span>
        <br><span>{!! !empty($contact->message_body) ? nl2br($contact->message_body) : '' !!}</span>
    </div>
</div>

<div class="col-md-4" style="background-color:#eeeeee;padding: 25px;width: fit-content;margin-left:20px">
    {!! Form::label('mail_sent_count', __('cmsadmin::models/contacts.fields.mail_sent_count') . ':') !!} <br>
    <span><?= isset($contact->mail_sent_count) ? $contact->mail_sent_count : '' ?></span>

    <br> {!! Form::label('mail_sent_on', __('cmsadmin::models/contacts.fields.mail_sent_on') . ':') !!} <br>
    <span><?= !empty($contact->mail_sent_on) ? $contact->mail_sent_on : 'NOT SENT' ?></span>
</div>
