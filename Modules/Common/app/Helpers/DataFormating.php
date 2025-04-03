<?php

/**
 * Custom Data Formatting Helper Functions
 * /app/Helpers/DataFormating.php
 */

use Illuminate\Support\Str;

/**
 * @param  date  $dateData
 * @param  string  $dateFormat
 * @return formated date
 */
function dateFormatter($dateData = null, $dateFormat = DEFAULT_DATE_FORMAT)
{
    if ($dateData) {
        $dt = new DateTime($dateData);

        return $dt->format($dateFormat);
    } else {
        return '';
    }
}

function bytesToHuman($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

    for ($i = 0; $bytes > 1024; $i++) {
        $bytes /= 1024;
    }

    return round($bytes, 2) . ' ' . $units[$i];
}

/**
 * trim string/text
 */
function trimText($string, $len = DEFAULT_TRIM_LEN, $stripTags = '', $end = '...')
{
    if (empty($string)) {
        return '';
    }

    if ($stripTags == '') {
        $stripText = $string;
    } elseif ($stripTags == 'all') {
        $stripText = Str::of($string)->stripTags();
    } else {
        $stripText = Str::of($string)->stripTags($stripTags);
    }

    return Str::limit($stripText, $len, $end);
}

function getOldData($key, $defaultValue = 2)
{
    return !empty(old($key)) ? old($key) : $defaultValue;
}
