<?php

/**
 * constants-csp.php
 */
define('SELECT_DIRECTIVES', [
    'base-uri' => 'base-uri',
    'connect-src' => 'connect-src',
    'default-src' => 'default-src',
    'font-src' => 'font-src',
    'form-action' => 'form-action',
    'frame-ancestors' => 'frame-ancestors',
    'frame-src' => 'frame-src',
    'img-src' => 'img-src',
    'manifest-src' => 'manifest-src',
    'media-src' => 'media-src',
    'object-src' => 'object-src',
    'script-src' => 'script-src',
    'style-src' => 'style-src',
    'worker-src' => 'worker-src',
]);

define('SELECT_KEYWORDS', [
    'self' => 'self',
    'unsafe-eval' => 'unsafe-eval',
    'unsafe-inline' => 'unsafe-inline',
]);

define('SELECT_SCHEMAS', [
    'blob:' => 'blob:',
    'data:' => 'data:',
]);
