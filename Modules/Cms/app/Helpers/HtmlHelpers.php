<?php

function detailHtml($detail, $folderName, $size = 600, $defaultImage = DEFAULT_IMAGE_SIZE_600, $wrapperClass = 'col-lg-12')
{
    $imgClass = '';
    if ($detail->image) {
        $imgClass = 'multi-data-layout ';
        switch ($detail->layout) {
            case 2:
                $imgClass .= 'right';
                break;

            case 3:
                $imgClass .= 'top';
                break;

            case 4:
                $imgClass .= 'bottom';
                break;

            default:
                $imgClass .= 'left';
                break;
        }
    }

    $html = '<div class="' . $wrapperClass . '">';
    $html .= '<h3>' . $detail->title . '</h3>';
    $html .= '<div class="' . $imgClass . '">';
    if ($detail->image) {
        $html .= '<figure>';
        if (file_exists(STORAGE_DIR_NAME . DS . $folderName . DS . $size . DS . $detail->image)) {
            $html .= renderImage($folderName, $detail->image, $size, $imgClass);
        } else {
            $html .= '<img class="' . $imgClass . '" src="' . asset($defaultImage) . '" alt="' . $detail->title . '">';
        }
        $html .= '</figure>';
    }
    $html .= '<div class="editorData">' . $detail->detail . '</div>';
    $html .= '</div>';
    $html .= '</div>';

    return $html;
}
