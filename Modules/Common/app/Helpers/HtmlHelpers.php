<?php

/**
 * Html Helper Functions
 * /app/Helpers/HtmlHelpers.php
 */

/**
 * @param  string  $label
 * @param  string  $route
 * @param  string  $faClass  fontawesome class: fas, solid
 * @param  string  $btnClass
 * @param  string  $btnBgClass
 * @param  string  $btnId
 * @return string html
 */
function renderLinkButton($label = 'Button', $route = null, $faClass = 'question', $btnClass = 'default', $btnBgClass = 'purple', $btnId = '')
{
    $html = '';
    if (!empty($route)) {
        $html = "<a href='$route' class='btn btn-sm btn-$btnClass $btnBgClass' id='$btnId'><i class='fas fa-$faClass'></i> $label</a>";
    }

    return $html;
}

function renderPreviewButton($formId = null, $url = null, $postId = null)
{
    if (env('PREVIEW_BUTTON_ENABLE', true) == false) {
        return '';
    }

    $routeUrl = $postId ? route($url, $postId) : route($url);

    $html = "<button type='button' class='btn bg-purple preview-btn' id='preview' 
                onclick='submitPreview(\"$formId\", \"$routeUrl\")'>
                <i class='fas fa-desktop'></i> " . __('common::crud.preview') . '
            </button>';

    return $html;
}

/**
 * @param  string  $label
 * @param  int  $id
 * @param  string  $route
 * @param  string  $faClass  fontawesome class: fas, solid
 * @param  string  $btnClass
 * @param  string  $btnBgClass
 * @param  string  $btnId
 * @return string html
 */
function renderLinkButtonWithId($label = 'Button', $id = null, $route = null, $faClass = 'question', $btnClass = 'default', $btnBgClass = 'purple', $btnId = '')
{
    $html = '';
    if (!empty($route)) {
        $html = "<a data-id='$id' href='$route' class='btn btn-sm btn-$btnClass $btnBgClass' id='$btnId'><i class='fas fa-$faClass'></i> $label</a>";
    }

    return $html;
}

/**
 * @param  string  $label
 * @param  string  $type
 * @param  string  $btnClass
 * @param  string  $btnBgClass
 * @param  string  $faClass  fontawesome class
 * @return string html
 */
function renderButton($label = 'Save', $type = 'submit', $btnClass = 'default', $btnBgClass = 'lime', $faClass = 'circle', $jsScript = '')
{
    $jsHtml = (!empty($jsScript)) ? " onclick='javascript:$jsScript'" : '';
    $html = "<button type='$type' class='btn btn-sm btn-$btnClass $btnBgClass' value='$label' $jsHtml><i class='fa fa-$faClass'></i> $label</button>";

    return $html;
}

/**
 * @param  string  $label
 * @param  string  $btnClass
 * @param  string  $btnBgClass
 * @return string html
 */
function renderSubmitButton($label = 'Save', $btnClass = 'success', $btnBgClass = 'lime', $jsScript = 'form.submit();')
{
    $html = "<button type='button' class='btn btn-sm btn-$btnClass $btnBgClass' value='$label'  onclick='javascript:$jsScript'><i class='fa fa-save'></i> $label</button>";

    return $html;
}

/**
 * @param  string  $label
 * @param  string  $route
 * @param  string  $faClass  fontawesome class
 * @param  string  $btnClass
 * @param  string  $btnBgClass
 * @param  string  $btnId
 * @return string html
 */
function renderIconButton($label = 'Button', $route = null, $faClass = 'invalid', $btnClass = 'default', $btnBgClass = 'lime', $btnId = '')
{
    $html = '';
    if (!empty($route)) {
        $html = "<a href='$route' class='btn btn-sm btn-$btnClass $btnBgClass' id='$btnId'><i class='fa fa-$faClass' aria-hidden='true'></i> $label</a>";
    }

    return $html;
}

/**
 * @param  string  $route
 * @param  string  $faClass  fontawesome class
 * @param  string  $tooltip
 * @param  string  $btnSizeClass
 * @param  string  $btnBgClass
 * @return string html
 */
function renderActionIcon($route = null, $faClass = 'invalid', $tooltip = '', $btnBgClass = 'default', $btnSizeClass = 'xs')
{
    $html = '';
    if (!empty($route)) {
        $html = "<a href='$route' class='btn btn-sm btn-$btnSizeClass $btnBgClass' title='$tooltip'><i class='fa fa-$faClass'></i></a>";
    }

    return $html;
}

/**
 * @param  int  $id
 * @param  string  $route
 * @param  string  $faClass  fontawesome class
 * @param  string  $tooltip
 * @param  string  $btnSizeClass
 * @param  string  $btnBgClass
 * @return string html
 */
function renderActionIconWithId($id = null, $route = null, $faClass = 'invalid', $tooltip = '', $btnBgClass = 'default', $btnSizeClass = 'xs')
{
    $html = '';
    if (!empty($route)) {
        $html = "<a data-id='$id' href='$route' class='btn btn-sm btn-$btnSizeClass $btnBgClass' title='$tooltip'><i class='fa fa-$faClass'></i></a>";
    }

    return $html;
}

/**
 * @param  string  $route
 * @param  string  $attribute
 * @param  string  $btnClass
 * @param  string  $btnSizeClass
 * @return string html
 */
function renderImageDeleteIcon($route = null, $attribute = null, $btnClass = 'danger', $btnSizeClass = 'xs')
{
    $html = '';
    if (!empty($route) && !empty($attribute)) {
        $html = "<a data-route='$route' data-attrib='$attribute' class='remove-image btn btn-sm btn-$btnClass btn-$btnSizeClass'><i class='fa fa-trash'></i></a>";
    }

    return $html;
}

/**
 * @param  string  $imageName
 * @param  string  $btnClass
 * @param  string  $btnSizeClass
 * @return string html
 */
function renderTmpImageDeleteIcon($imageName = null, $btnClass = 'danger', $btnSizeClass = 'xs')
{
    $html = '';
    if (!empty($imageName)) {
        $html = "<a data-route='" . route('common.imageHandler.destroy') . "' data-name='$imageName' class='remove-tmp-image btn btn-sm btn-$btnClass btn-$btnSizeClass'><i class='fa fa-trash'></i></a>";
    }

    return $html;
}

/**
 * @param  string  $dirName
 * @param  string  $imageName
 * @param  int  $size
 * @return string html
 */
function renderImage($dirName = null, $imageName = null, $size = 100, $className = '')
{
    $html = '';
    if (!empty($dirName) && !empty($imageName)) {
        $url = url("/storage/$dirName/$size/$imageName");
        $html = "<img class='$className' src='$url' alt='$imageName' />";
    }

    return $html;
}

/**
 * @param  string  $imagePath
 * @param  string  $title
 * @param  string  $defaultImgPath
 * @return string html
 */
function renderImageWithNoImage($imagePath, $title, $defaultImgPath = DEFAULT_IMAGE_SIZE_600, $className = '')
{
    $html = '';
    if ($imagePath) {
        if (!is_dir($imagePath) && file_exists($imagePath)) {
            $url = asset($imagePath);
        } else {
            $url = asset($defaultImgPath);
        }
        $html = "<img class='$className' src='$url' alt='$title' />";
    }

    return $html;
}

/**
 * @param  string  $imageName
 * @param  string  $className
 * @return string html
 */
function renderTmpImage($imageName = null, $size = 100, $className = '')
{
    $html = '';
    if (!empty($imageName)) {
        $imageSrc = imageToBase64(storage_path('tmp/' . $imageName));
        $html = "<img class='$className' src='$imageSrc' alt='$imageName' width='$size' />";
    }

    return $html;
}

/**
 * @param  string  $fileName
 * @param  int  $ext
 * @return string html
 */
function renderFileIcon($fileName, $ext = null, $className = '')
{
    $html = '';
    if (!empty($fileName)) {
        $ext = !empty($ext) ? $ext : pathinfo($fileName, PATHINFO_EXTENSION);
        $url = url('/img/icons/file-types/' . $ext . '.png');
        $html = "<img class='$className' src='$url' alt='$fileName' width='100' />";
    }

    return $html;
}

/**
 * @param  string  $dirName
 * @param  string  $imageName
 * @param  int  $size
 * @param  string  $imgClass
 * @return string html
 */
function renderFancyboxImage($dirName = null, $imageName = null, $thumbSize = 200, $bigSize = 800, $imgClass = 'img-fluid image scale-on-hover', $defaultImage = DEFAULT_IMAGE_SIZE_600)
{
    $html = '';

    if (!empty($dirName) && !empty($imageName)) {
        $imagePath = public_path("storage/$dirName/$imageName");

        if (file_exists($imagePath)) {
            $href = url("storage/$dirName/$bigSize/$imageName");
            $imgSrcUrl = url("storage/$dirName/$thumbSize/$imageName");
        } else {
            $href = url($defaultImage);
            $imgSrcUrl = url($defaultImage);
        }

        $html = "<a data-fancybox='gallery' href='$href'>
                    <img src='$imgSrcUrl' class='$imgClass'>
                 </a>";
    }

    return $html;
}

/**
 * @param  string  $imageName
 * @param  int  $size
 * @param  string  $imgClass
 * @return string html
 */
function renderFancyboxTmpImage($imageName = null, $size = 200, $imgClass = 'img-fluid image scale-on-hover')
{
    $html = '';
    if (!empty($imageName)) {
        $imageSrc = imageToBase64(storage_path('tmp/' . $imageName));
        $html = "<a data-fancybox='gallery' href='$imageSrc'>
			<img src='$imageSrc' width='$size' class='$imgClass'>
			</a>";
    }

    return $html;
}

/**
 * @param  string  $dirName
 * @param  string  $imageName
 * @param  string  $href
 * @param  int  $size
 * @param  string  $className
 * @return string html
 */
function linkWithImage($dirName = null, $imageName = null, $href = '#', $className = '', $size = 200)
{
    $html = '';
    if (!empty($dirName) && !empty($imageName)) {
        $url = url("/storage/$dirName/$size/$imageName");
        $style = "background-image: url($url)";
        $html = "<a href='$href' class='$className' style='$style'></a>";
    }

    return $html;
}

/**
 * @param  string  $dirName
 * @param  string  $imageName
 * @param  int  $size
 * @param  string  $imgClass
 * @param  string  $hrefClass
 * @return string html
 */
function renderGalleryImage($dirName = null, $imageName = null, $size = 200, $imgClass = 'img-fluid image scale-on-hover', $hrefClass = 'lightbox')
{
    $html = '';
    if (!empty($dirName) && !empty($imageName)) {
        $href = $imgSrcUrl = url("/storage/$dirName/$size/$imageName");
        $html = "<a class='$hrefClass' href='$href'>
			<img class='$imgClass' src='$imgSrcUrl'>
			</a>";
    }

    return $html;
}

/**
 * @param  string  $name
 * @param  int  $id
 * @param  int  $value
 * @param  string  $url
 * @param  string  $inputClass
 * @return string html
 */
function renderBsSwitchGrid($name, $id, $value, $url = '', $inputClass = '')
{
    $checked = ($value == 1) ? 'checked' : '';

    echo "<div class='form-group $inputClass'><input type='hidden' name='$name' value='2'><div><label for='$name'></label><input type='checkbox' class='" . $name . "_toggle' name='$name' value='1' $checked data-route='$url' data-id='$id'/></div></div>";
}

/**
 * @param  string  $name
 * @param  int  $id
 * @param  int  $value
 * @param  string  $url
 * @param  string  $inputClass
 * @return string html
 */
function renderBsSwitchGridEx($name, $id, $value, $url = '', $inputClass = '')
{
    $checked = ($value == 1) ? 'checked' : '';

    return "<div class='form-group $inputClass'><input type='hidden' name='$name' value='2'><div><label for='$name'></label><input type='checkbox' class='" . $name . "_toggle' name='$name' value='1' $checked data-route='$url' data-id='$id'/></div></div>";
}

/**
 * @param  array  $items
 * @param  string  $cssClass
 * @return string html
 */
function renderBulkActions($items = null, $cssClass = '')
{
    $html = '<select id="bulkActions" class="form-control-sm ' . $cssClass . '">';
    $html .= '<option value="0">' . __('common::crud.text.select_any') . '</option>';
    if (!empty($items) && is_array($items)) {
        foreach ($items as $key => $item) {
            $label = isset($item['label']) ? $item['label'] : '';
            $url = isset($item['url']) ? $item['url'] : '';
            $value = isset($item['value']) ? $item['value'] : '';
            if ($label && $url && $value) {
                $html .= '<option value="' . $value . '" data-url="' . $url . '">' . $label . '</option>';
            }
        }
    }
    $html .= '</select>';

    return $html;
}

/**
 * @param  string  $routeName
 * @param  int  $id
 * @param  bool  $notReserved
 * @param  array  $routeParams
 * @return string html
 */
function renderDeleteBtn($routeName, $id, $notReserved = true, $routeParams = null, $faClass = 'trash', $permanent = null)
{
    $html = '';
    if ($notReserved) {
        $html = Form::open([
            'route' => [$routeName, !empty($routeParams) ? $routeParams : $id],
            'method' => 'delete',
            'id' => "deleteform_$id",
        ]);

        $html .= "<input type='hidden' name='id' value='$id' />";

        // Build the onclick attribute with $permanent only if it's not null
        $onclick = "return confirmDelete(event, 'deleteform_$id'";
        if (!is_null($permanent)) {
            $onclick .= ", '$permanent'";
        }
        $onclick .= ')';

        $html .= '<button type="submit" class="btn btn-sm btn-danger" onclick="' . $onclick . '">';
        $html .= '<i class="mr-1 fa fa-' . $faClass . '"></i>' . __('common::crud.delete') . '</button>';
        $html .= Form::close();
    }

    return $html;
}
