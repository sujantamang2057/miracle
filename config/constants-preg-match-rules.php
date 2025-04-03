<?php

/**
 * constants-preg-match-rules.php
 */
// preg_match patterns / rules
define('PREG_PHONE_NO', '/^[0-9-+]+$/');
define('PREG_NOSPACE', "/^\S*$/");
define('PREG_INT_NO', "/^[+]?\d+$/");
define('PREG_URL', "/[(http(s)?):\/\/(www\.)?a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)/");
define('PREG_URL_EX', "/((https?|ftp)\:\/\/)?([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?([a-z0-9-.]*)\.([a-z]{2,3})(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?/");
// restrict these backslash space . + * ^ $ ( ) |
define('PREG_URL_INTERNAL_EX', "/[\\\\\s$^(*.+)|]/");
define('PREG_EXTERNAL_URL', "/[(http(s)?):\/\/(www\.)?a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)/");
// Supplementary Multilingual Plane
define('PREG_SMP', '|[\x{10000}-\x{10FFFF}]|u');
define('PREGR_SLUG_DEFAULT', "/[!\"`'#%&,:;<>=@{}~\$\(\)\*\+\/\\\?\[\]\^\|]+/");

// String & Numeric
define('PREG_NUMERIC_ONLY', '/^[0-9]+$/');
define('PREG_ALPHA_ONLY', '/^[a-zA-Z]+$/');
define('PREG_ALPHA_NUMERIC', '/^[a-zA-Z0-9]+$/');
define('PREG_ALPHA_NUMERIC_COMMA', '/^[a-zA-Z0-9,]+$/');
define('PREG_ALPHA_NUMERIC_UNDERSCORE', '/^[a-zA-Z0-9_]+$/');
// Small Alphabets
define('PREG_SMALL_ALPHA_ONLY', '/^[a-z]+$/');
define('PREG_SMALL_ALPHA_NUMERIC', '/^[a-z0-9]+$/');
define('PREG_SMALL_ALPHA_NUMERIC_COMMA', '/^[a-z0-9,]+$/');
define('PREG_SMALL_ALPHA_NUMERIC_UNDERSCORE', '/^[a-z0-9_]+$/');
// Module Specific
define('PREG_BLOCK_FILE_NAME', '/^[a-z0-9_-]+$/');
define('PREG_EMAIL_TEMPLATE_CODE', '/^[A-Z0-9-]+$/');
define('PREG_SEO_CODE', '/^[A-Z-]+$/');
define('PREG_SITE_SETTING_TEL', '/^\+?[0-9\s\-]+$/');
