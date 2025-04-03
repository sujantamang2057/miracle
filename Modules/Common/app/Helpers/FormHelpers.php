<?php

/**
 * Form Helper Functions
 * /app/Helpers/FormHelpers.php
 */

/**
 * form validation div class
 *
 * @param  bool  $hasError
 * @return string
 */
function validationClass($hasError)
{
    echo ($hasError) ? ' has-error' : ' has-success';
}

/**
 * form validation input class
 *
 * @param  bool  $hasError
 * @return string
 */
function validationInputClass($hasError)
{
    return 'form-control' . ($hasError ? ' is-invalid' : '');
}

/**
 * form validation message
 *
 * @param  string  $errorMsg
 * @return string
 */
function validationMessage($errorMsg = '')
{
    if (!empty($errorMsg)) {
        echo "<p class='text-danger'>{$errorMsg}</p>";
    } else {
        echo '';
    }
}
