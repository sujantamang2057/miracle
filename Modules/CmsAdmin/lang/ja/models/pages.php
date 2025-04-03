<?php

return [
    'singular' => 'ページ管理',
    'plural' => 'ページ一覧',
    'fields' => [
        'page_id' => 'ID',
        'page_title' => 'タイトル',
        'slug' => 'SLUG',
        'page_type' => 'ページタイプ',
        'page_file_name' => 'ページファイル名',
        'meta_keyword' => 'メタキー',
        'meta_description' => 'メタ説明',
        'description' => '説明',
        'banner_image' => 'バナー画像',
        'published_date' => '公開日',
    ],
    'page_multidata' => [
        'singular' => 'ページ複数行一覧',
        'plural' => 'ページ複数行一覧',
    ],
    // option value texts
    'type_static' => '静的なページ',
    'type_dynamic' => '動的なページ',
    'message' => [
        'file_regeneration_failed' => 'ページ再生成に失敗しました。',
    ],
    'optimal_image_size' => '最適な画像サイズ: 600*500',
];
