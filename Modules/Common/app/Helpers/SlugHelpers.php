<?php

/**
 * Slug Helper Functions
 * /app/Helpers/SlugHelpers.php
 */
function _toSlugTransliterate($string)
{
    // Lowercase equivalents found at:
    // https://github.com/kohana/core/blob/3.3/master/utf8/transliterate_to_ascii.php
    // additional equivalents found at
    // https://github.com/cocur/slugify/blob/main/src/RuleProvider/DefaultRuleProvider.php
    $transliterate = [
        '°' => '0', '¹' => '1', '²' => '2', '³' => '3', '⁴' => '4', '⁵' => '5', '⁶' => '6',
        '⁷' => '7', '⁸' => '8', '⁹' => '9', '₀' => '0', '₁' => '1', '₂' => '2', '₃' => '3',
        '₄' => '4', '₅' => '5', '₆' => '6', '₇' => '7', '₈' => '8', '₉' => '9', 'æ' => 'ae',
        'ǽ' => 'ae', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Å' => 'AA', 'Ǻ' => 'A',
        'Ă' => 'A', 'Ǎ' => 'A', 'Æ' => 'AE', 'Ǽ' => 'AE', 'ā' => 'a', 'à' => 'a', 'á' => 'a',
        'â' => 'a', 'ą' => 'a', 'å' => 'a', 'ä' => 'a', 'ã' => 'a', 'å' => 'aa', 'ǻ' => 'a',
        'ă' => 'a', 'ǎ' => 'a', 'ª' => 'a', '@' => 'at', 'ḃ' => 'b', 'Ĉ' => 'C', 'Ċ' => 'C',
        'Ç' => 'C', 'č' => 'c', 'ć' => 'c', 'ç' => 'c', 'ĉ' => 'c', 'ċ' => 'c', '©' => 'c',
        'Ð' => 'Dj', 'Đ' => 'D', 'ð' => 'dj', 'ď' => 'd', 'đ' => 'd', 'ḋ' => 'd', 'ð' => 'dh',
        'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'ě' => 'e',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ę' => 'e', 'ē' => 'e', 'ë' => 'e', 'ĕ' => 'e',
        'ė' => 'e', 'ƒ' => 'f', 'ḟ' => 'f', 'Ĝ' => 'G', 'Ġ' => 'G', 'ğ' => 'g', 'ĝ' => 'g',
        'ġ' => 'g', 'ģ' => 'g', 'Ĥ' => 'H', 'Ħ' => 'H', 'ĥ' => 'h', 'ħ' => 'h', 'Ì' => 'I',
        'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Ǐ' => 'I', 'Į' => 'I',
        'Ĳ' => 'IJ', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ĩ' => 'i', 'ĭ' => 'i',
        'ǐ' => 'i', 'į' => 'i', 'ī' => 'i', 'ı' => 'i', 'ĳ' => 'ij', 'Ĵ' => 'J', 'ĵ' => 'j',
        'ķ' => 'k', 'Ĺ' => 'L', 'Ľ' => 'L', 'Ŀ' => 'L', 'ĺ' => 'l', 'ł' => 'l', 'ļ' => 'l',
        'ľ' => 'l', 'ŀ' => 'l', 'ṁ' => 'm', 'Ñ' => 'N', 'ņ' => 'n', 'ň' => 'n', 'ñ' => 'n',
        'ŉ' => 'n', 'ń' => 'n', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ō' => 'O',
        'Ŏ' => 'O', 'Ǒ' => 'O', 'Ő' => 'O', 'Ơ' => 'O', 'Ø' => 'OE', 'Ǿ' => 'O', 'Œ' => 'OE',
        'ø' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ō' => 'o',
        'ŏ' => 'o', 'ǒ' => 'o', 'ő' => 'o', 'ơ' => 'o', 'ø' => 'oe', 'ǿ' => 'o', 'º' => 'o',
        'œ' => 'oe', 'ṗ' => 'p', 'Ŕ' => 'R', 'Ŗ' => 'R', 'ř' => 'r', 'ŕ' => 'r', 'ŗ' => 'r',
        'Ŝ' => 'S', 'Ș' => 'S', 'ş' => 's', 'ś' => 's', 'ṡ' => 's', 'š' => 's', 'ŝ' => 's',
        'ș' => 's', 'ſ' => 's', 'ß' => 'ss', 'Ţ' => 'T', 'Ț' => 'T', 'Ŧ' => 'T', 'Þ' => 'TH',
        'ť' => 't', 'ṫ' => 't', 'ţ' => 't', 'ț' => 't', 'ŧ' => 't', 'þ' => 'th', 'Ù' => 'U',
        'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ũ' => 'U', 'Ŭ' => 'U', 'Ű' => 'U', 'Ų' => 'U',
        'Ư' => 'U', 'Ǔ' => 'U', 'Ǖ' => 'U', 'Ǘ' => 'U', 'Ǚ' => 'U', 'Ǜ' => 'U', 'ū' => 'u',
        'ù' => 'u', 'ú' => 'u', 'ů' => 'u', 'û' => 'u', 'ü' => 'u', 'ũ' => 'u', 'ŭ' => 'u',
        'ű' => 'u', 'ų' => 'u', 'ư' => 'u', 'ǔ' => 'u', 'ǖ' => 'u', 'ǘ' => 'u', 'ǚ' => 'u',
        'ǜ' => 'u', 'µ' => 'u', 'Ŵ' => 'W', 'ẁ' => 'w', 'ŵ' => 'w', 'ẃ' => 'w', 'ẅ' => 'w',
        'Ý' => 'Y', 'Ÿ' => 'Y', 'Ŷ' => 'Y', 'ỳ' => 'y', 'ý' => 'y', 'ÿ' => 'y', 'ŷ' => 'y',
        'ž' => 'z', 'ż' => 'z', 'ź' => 'z',
    ];

    return str_replace(array_keys($transliterate), array_values($transliterate), $string);
}

function generateSeoLinks($string, $separator = '-')
{
    // $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
    // convert to ascii code
    $string = _toSlugTransliterate($string);
    // Remove unwanted chars + trim excess whitespace
    $string = preg_replace(PREG_SMP, $separator, $string);
    $string = preg_replace(PREGR_SLUG_DEFAULT, $separator, $string);
    // hack for Unicode text problem for some earlier PHP versions -mi20150917
    $string = mb_strtolower($string, 'UTF-8');
    // Same as before
    $string = preg_replace("/[ {$separator}]+/", $separator, $string);

    return $string;
}
