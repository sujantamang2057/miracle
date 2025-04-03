<?php

/**
 * URL Helper Functions
 * /app/Helpers/UrlHelpers.php
 */

use Illuminate\Support\Facades\Storage;

// Get the base URL
function baseURL()
{
    $url = url('/');

    return $url;
}

// Get the base URL
function baseRootURL()
{
    $url = request()->root();

    return $url;
}

// Get the app URL from configuration which we set in .env file
function appURL()
{
    $url = config('app.url');

    return $url;
}

// Get the current URL without the query string
function currentURL()
{
    $url = url()->current();

    return $url;
}

// Get the current URL including the query string
function fullURL()
{
    $url = url()->full();

    return $url;
}

// Get the full URL for the previous request
function previousURL()
{
    $url = url()->previous();

    return $url;
}

// stored in /storage/app/public
function fileURL($imageName, $extraPath = null)
{
    $imagePath = !empty($extraPath) ? $extraPath . $imageName : $imageName;
    $fileUrl = Storage::url($imagePath);

    return $fileUrl;
}
