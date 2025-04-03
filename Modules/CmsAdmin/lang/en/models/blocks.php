<?php

return [
    'singular' => 'Block',
    'plural' => 'Blocks',
    'fields' => [
        'block_id' => 'Block ID',
        'block_name' => 'Block Name',
        'filename' => 'Filename',
        'file_contents' => 'File Contents',
    ],
    'message' => [
        'file_regeneration_failed' => 'Failed to regenerate block files',
    ],
    // validation msg
    'validation' => [
        'filename.regex' => 'Filename can contain small alphabets, numbers, underscore & dash only',
    ],
];
