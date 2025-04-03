<?php

return [
    'singular' => 'メールテンプレート',
    'plural' => 'メールテンプレート一覧',
    'fields' => [
        'template_id' => 'ID',
        'name' => '名称',
        'mail_code' => 'メールコード',
        'subject' => '件名',
        'variables' => '変数',
        'contents' => 'コンテンツ',
        'publish' => '公開',
        'reserved' => '削除制御',
        'created_at' => '作成日',
        'created_by' => '作成者',
        'updated_at' => '更新日',
        'updated_by' => '更新者',
        'deleted_at' => '削除日',
        'deleted_by' => '削除者',
    ],
    'message' => [
        'variables_exceeded_field_limit' => '変数がフィールドの制限を超えています。',
        'email_regeneration_failed' => 'メール再生成に失敗しました。',
    ],
    // validation msg
    'validation' => [
        'mail_code.regex' => 'Mail Code can contain capital alphabets, numbers & dash only',
    ],
];
