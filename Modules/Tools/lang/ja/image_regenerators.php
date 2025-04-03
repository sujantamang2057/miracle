<?php

return [
    'fields' => [
        'module_name' => 'モジュール名',
        'image_name' => '画像名',
        'limit' => 'ファイル数制限',
    ],
    'messages' => [
        'regenerate_error' => '生成に失敗しました。',
        'regenerate_success' => '生成しました。',
        'regenerate_error' => '生成に失敗しました。',
        'image_regenerating' => '画像は生成中です。しばらくお待ちして下さい。',
        'image_missing_database' => '#ID :Id はデータベース上で画像は存在しません。',
        'image_missing' => '#ID :Id <b>:FileName</b> ファイルは存在しません。',
        'image_regenerated' => '#ID :Id <b>:FileName</b> 再生成されたファイルサイズ <b>:Sizes</b>',
    ],
];
