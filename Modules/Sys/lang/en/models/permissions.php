<?php

return [
    'singular' => 'Permission',
    'plural' => 'Permissions',
    'fields' => [
        'id' => 'ID',
        'name' => 'Permission Name',
        'guard_name' => 'Guard Name',
        'module_name' => 'Module',
    ],
    'btn' => [
        'scan_permissions' => 'Scan Permissions',
    ],
    'text' => [
        'move_all_to_allowed' => 'Move all to allowed',
        'move_all_to_restricted' => 'Move all to restricted',
        'move_selected_to_allowed' => 'Move selected to allowed',
        'move_selected_to_restricted' => 'Move selected to restricted',
        'scan' => 'Scan',
        'scan_route_permissions' => 'Scan Routes Permissions',
        'scan_modules_text' => 'You can scan your modules to find routes and create permissions for them automatically.',
    ],
    'messages' => [
        'routes_scanned' => ':Count routes scanned successfully for :Module',
        'no_new_routes_found' => 'No new routes found for :Module',
    ],
];
