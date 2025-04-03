<?php

/**
 * String Helper Functions
 * /app/Helpers/StringHelpers.php
 */

use Illuminate\Support\Str;

/**
 * @param  string  $string
 * @param  string|array  $strToRemove
 * @return string formatted
 */
function removeString($string = '', $strToRemove = null, $replaceWith = '')
{
    if (is_array($strToRemove)) {
        foreach ($strToRemove as $val) {
            $string = Str::of($string)->replace($val, $replaceWith);
        }
    } else {
        $string = Str::of($string)->replace($strToRemove, $replaceWith);
    }

    return (string) $string;
}
