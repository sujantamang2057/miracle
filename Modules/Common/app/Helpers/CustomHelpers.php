<?php

/**
 * Custom Helper Functions
 * /app/Helpers/CustomHelpers.php
 */

/**
 * Debugger
 *
 * @param  array  $arrayData
 * @param  bool  $exit
 * @return void
 */
function prePrint($arrayData, $exit = true)
{
    echo '<pre>';
    print_r($arrayData);
    echo '</pre>';
    if ($exit === true) {
        exit();
    }
}

/**
 * Logger - Info
 *
 * @param  string  $logLabel
 * @param  array  $logData
 * @return void
 */
function logInfo($logLabel, $logData = [])
{
    $logDataArray = (is_array($logData)) ? $logData : (array) ($logData);
    \Log::info($logLabel, $logDataArray);
}

/**
 * Logger - Warning
 *
 * @param  string  $logLabel
 * @param  array  $logData
 * @return void
 */
function logWarning($logLabel, $logData = [])
{
    $logDataArray = (is_array($logData)) ? $logData : (array) ($logData);
    \Log::warning($logLabel, $logDataArray);
}

/**
 * Logger - Error
 *
 * @param  string  $logLabel
 * @param  array  $logData
 * @return void
 */
function logError($logLabel, $logData = [])
{
    $logDataArray = (is_array($logData)) ? $logData : (array) ($logData);
    \Log::error($logLabel, $logDataArray);
}
