<?php

/**
 * Widget Config Helper Functions
 * /app/Helpers/WidgetConfigHelpers.php
 */

// daterangepicker
function getSingleDateTimerPickerConfig($showTimePicker = false)
{
    $format = ($showTimePicker) ? DATE_RANGE_PICKER_DATETIME_FORMAT : DATE_RANGE_PICKER_DATE_FORMAT;
    $config = [
        'timePicker' => $showTimePicker,
        'autoUpdateInput' => false,
        'singleDatePicker' => true,
        'showDropdowns' => true,
        'applyButtonClasses' => 'btn-success',
        'cancelButtonClasses' => 'btn-danger',
        'locale' => [
            'format' => $format,
            'applyLabel' => __('common::daterangepicker.apply'),
            'cancelLabel' => __('common::daterangepicker.cancel'),
        ],
    ];

    return $config;
}
