<?php

return [
    'singular' => '権限',
    'plural' => '権限',
    'fields' => [
        'id' => 'ID',
        'name' => '権限名',
        'guard_name' => 'ガード名',
        'module_name' => 'モジュール',
    ],
    'btn' => [
        'scan_permissions' => 'スキャン権限',
    ],
    'text' => [
        'move_all_to_allowed' => 'すべて許可済みに移動',
        'move_all_to_restricted' => 'すべてを制限に移動',
        'move_selected_to_allowed' => '選択したものを許可済みに移動',
        'move_selected_to_restricted' => '選択したものを制限に移動',
        'scan' => 'スキャン',
        'scan_route_permissions' => 'スキャンルート権限',
        'scan_modules_text' => 'モジュールをスキャンしてルートを見つけ、それらの権限を自動的に作成できます。',
    ],
    'messages' => [
        'routes_scanned' => ':Count ルートが正常にスキャンされました:Module',
        'no_new_routes_found' => '新しいルートが見つかりません :Module',
    ],
];
