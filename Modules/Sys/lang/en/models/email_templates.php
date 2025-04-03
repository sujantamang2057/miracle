<?php

return [
    'singular' => 'Email Template',
    'plural' => 'Email Templates',
    'fields' => [
        'template_id' => 'Template Id',
        'name' => 'Name',
        'mail_code' => 'Mail Code',
        'subject' => 'Subject',
        'variables' => 'Variables',
        'contents' => 'Contents',
        'publish' => 'Publish',
        'reserved' => 'Reserved',
        'created_at' => 'Created At',
        'created_by' => 'Created By',
        'updated_at' => 'Updated At',
        'updated_by' => 'Updated By',
        'deleted_at' => 'Deleted At',
        'deleted_by' => 'Deleted By',
    ],
    'message' => [
        'variables_exceeded_field_limit' => 'Variables exceeded the limit of field',
        'email_regeneration_failed' => 'Failed to regenerate email template',
    ],
    // validation msg
    'validation' => [
        'mail_code.regex' => 'Mail Code can contain capital alphabets, numbers & dash only',
    ],
];
