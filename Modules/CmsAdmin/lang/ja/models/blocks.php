<?php

return [
    'singular' => 'ブロック管理',
    'plural' => 'ブロック一覧',
    'fields' => [
        'block_id' => 'ブロックID',
        'block_name' => 'ブロック名',
        'filename' => 'ファイル名',
        'file_contents' => 'ファイルコンテンツ',
    ],
    'message' => [
        'file_regeneration_failed' => 'ブロック再生成に失敗しました。',
    ],
    // validation msg
    'validation' => [
        'filename.regex' => 'Filename can contain small alphabets, numbers, underscore & dash only',
    ],
];
