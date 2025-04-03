<?php

/**
 * FunctionsSQLs.php
 * /app/Helpers/FunctionsSQLs.php
 */

// function to return total records
function countRecords($modelName = null, $where = [])
{
    if (empty($modelName)) {
        return null;
    }

    $where['deleted_at'] = null;

    return $modelName::where($where)->count();
}
