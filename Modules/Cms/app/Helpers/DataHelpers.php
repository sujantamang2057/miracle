<?php

function getImageAlbumFigClass($key = null)
{
    $tempArray = [
        0 => 'style-center',
        1 => 'style-end',
        2 => 'style-top',
        3 => 'style-top-center',
        4 => 'style-vertical-right',
        5 => 'style-end-raleway',
    ];
    if (empty($key) && $key != 0) {
        return 'style-center';
    }
    if (array_key_exists($key, $tempArray)) {
        return $tempArray[$key];
    } else {
        return 'style-center';
    }
}

function getImageAlbumSpanClass($key = null)
{
    $tempArray = [
        0 => 'fn-holland',
        1 => 'fn-engraver',
        2 => 'fn-harrington',
        3 => 'fn-imprint',
        4 => 'fn-lora',
        5 => 'fn-raleway',
    ];
    if (empty($key) && $key != 0) {
        return 'fn-holland';
    }
    if (array_key_exists($key, $tempArray)) {
        return $tempArray[$key];
    } else {
        return 'fn-holland';
    }
}
