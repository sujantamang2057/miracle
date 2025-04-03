<?php

/**
 * Html Widget Helper Functions
 * /app/Helpers/HtmlWidgetHelpers.php
 * PB<pratap.baniya@gmail.com>
 */

/**
 * @param  string  $name
 * @param  int  $dataId
 * @param  int  $value
 * @param  string  $valueOld
 * @param  string  $dataSize  : mini, small, normal(default), large
 * @param  string  $dataHandleWidth  : 10px, 100px
 * @return string html
 */
function renderBootstrapSwitchYesNo($name = null, $dataId = null, $value = null, $valueOld = null, $dataSize = null, $dataHandleWidth = null)
{
    $dataOnText = __('common::general.yes');
    $dataOffText = __('common::general.no');
    $dataOnColor = 'success';
    $dataOffColor = 'danger';
    $value = ($valueOld == 'on' ? 1 : $value);

    return renderBootstrapSwitch($name, $dataId, $value, $dataOnText, $dataOffText, $dataOnColor, $dataOffColor, $dataSize, $dataHandleWidth);
}

function renderBootstrapSwitchOnOff($name = null, $dataId = null, $value = null, $valueOld = null, $dataSize = null, $dataHandleWidth = null)
{
    $dataOnText = __('common::general.on');
    $dataOffText = __('common::general.off');
    $dataOnColor = 'success';
    $dataOffColor = 'danger';
    $value = ($valueOld == 'on' ? 1 : $value);

    return renderBootstrapSwitch($name, $dataId, $value, $dataOnText, $dataOffText, $dataOnColor, $dataOffColor, $dataSize, $dataHandleWidth);
}

function renderBootstrapSwitchPublish($name = null, $dataId = null, $value = null, $valueOld = null, $dataSize = null, $dataHandleWidth = null)
{
    $dataOnText = __('common::general.publish_text_bs');
    $dataOffText = __('common::general.unpublish_text_bs');
    $dataOnColor = 'success';
    $dataOffColor = 'danger';
    $value = ($valueOld == 'on' ? 1 : $value);

    return renderBootstrapSwitch($name, $dataId, $value, $dataOnText, $dataOffText, $dataOnColor, $dataOffColor, $dataSize, $dataHandleWidth);
}

function renderBootstrapSwitchReserved($name = null, $dataId = null, $value = null, $valueOld = null, $dataSize = null, $dataHandleWidth = null)
{
    $dataOnText = __('common::general.reserved_text_bs');
    $dataOffText = __('common::general.nonreserved_text_bs');
    $dataOnColor = 'success';
    $dataOffColor = 'danger';
    $value = ($valueOld == 'on' ? 1 : $value);

    return renderBootstrapSwitch($name, $dataId, $value, $dataOnText, $dataOffText, $dataOnColor, $dataOffColor, $dataSize, $dataHandleWidth);
}

function renderBootstrapSwitchActive($name = null, $dataId = null, $value = null, $valueOld = null, $dataSize = null, $dataHandleWidth = null)
{
    $dataOnText = __('common::general.active_text_bs');
    $dataOffText = __('common::general.inactive_text_bs');
    $dataOnColor = 'success';
    $dataOffColor = 'danger';
    $value = ($valueOld == 'on' ? 1 : $value);

    return renderBootstrapSwitch($name, $dataId, $value, $dataOnText, $dataOffText, $dataOnColor, $dataOffColor, $dataSize, $dataHandleWidth);
}

function renderBootstrapSwitchUrlTarget($name = null, $dataId = null, $value = null, $valueOld = null, $dataSize = null, $dataHandleWidth = null)
{
    $dataOnText = __('common::general.target_self');
    $dataOffText = __('common::general.target_new');
    $dataOnColor = 'warning';
    $dataOffColor = 'info';
    $value = ($valueOld == 'on' ? 2 : $value);

    return renderBootstrapSwitch($name, $dataId, $value, $dataOnText, $dataOffText, $dataOnColor, $dataOffColor, $dataSize, $dataHandleWidth);
}

function renderBootstrapSwitchUrlType($name = null, $dataId = null, $value = null, $valueOld = null, $dataSize = null, $dataHandleWidth = null)
{
    $dataOnText = __('common::general.type_internal');
    $dataOffText = __('common::general.type_external');
    $dataOnColor = 'warning';
    $dataOffColor = 'info';
    $value = ($valueOld == 'on' ? 2 : $value);

    return renderBootstrapSwitch($name, $dataId, $value, $dataOnText, $dataOffText, $dataOnColor, $dataOffColor, $dataSize, $dataHandleWidth);
}

function renderBootstrapSwitchTrueFalse($name = null, $dataId = null, $value = null, $valueOld = null, $dataSize = null, $dataHandleWidth = null)
{
    $dataOnText = __('common::general.true');
    $dataOffText = __('common::general.false');
    $dataOnColor = 'success';
    $dataOffColor = 'danger';
    $value = ($valueOld == 'on' ? 1 : $value);

    return renderBootstrapSwitch($name, $dataId, $value, $dataOnText, $dataOffText, $dataOnColor, $dataOffColor, $dataSize, $dataHandleWidth);
}

/**
 * @param  string  $name
 * @param  int  $dataId
 * @param  int  $value
 * @param  string  $dataOnText
 * @param  string  $dataOffText
 * @param  string  $dataOnColor
 * @param  string  $dataOffColor
 * @param  string  $dataSize  : mini, small, normal(default), large
 * @param  string  $dataHandleWidth  : 10px, 100px
 * @return string html
 */
function renderBootstrapSwitch($name = null, $dataId = null, $value = null, $dataOnText = null, $dataOffText = null, $dataOnColor = null, $dataOffColor = null, $dataSize = null, $dataHandleWidth = null)
{
    $html = '';
    if (!empty($name)) {
        $checked = ($value === 1) ? 'checked' : '';
        $dataOnText = !empty($dataOnText) ? "data-on-text='$dataOnText'" : '';
        $dataOnColor = !empty($dataOnColor) ? "data-on-color='$dataOnColor'" : '';
        $dataOffText = !empty($dataOffText) ? "data-off-text='$dataOffText'" : '';
        $dataOffColor = !empty($dataOffColor) ? "data-off-color='$dataOffColor'" : '';

        $html = "<input
			type='checkbox'
			data-toggle='switch'
			name='$name'
			data-id='$dataId'
			data-size='$dataSize'
			data-handle-width='$dataHandleWidth'
			$dataOnText $dataOnColor
			$dataOffText $dataOffColor
			$checked />";
    }

    return $html;
}
