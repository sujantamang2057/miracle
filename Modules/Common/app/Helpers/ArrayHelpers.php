<?php

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Array Helper Functions
 * /app/Helpers/ArrayHelpers.php
 */
function convertCsvToIdTextArray($data, $selected = '')
{
    $dataArr = getArrFromStrArr($data);
    $selectedArr = getArrFromStrArr($selected);

    $tmpArr = null;
    if (!empty($dataArr)) {
        foreach ($dataArr as $key => $item) {
            $tmpArr[$key] = ['id' => $item, 'text' => $item];
            if (in_array($item, $selectedArr)) {
                $tmpArr[$key]['selected'] = true;
            }
        }
    }

    return json_encode($tmpArr);
}

function getArrFromStrArr($data)
{

    $dataArr = [];
    if (!empty($data)) {
        $dataArr = $data;
        if (!is_array($data)) {
            $dataArr = explode(',', $data);
        }
    }

    return $dataArr;
}

function getDropdownData($modelClass, $valueField, $labelField, $relation = null, $addExtraOption = false)
{

    if (in_array(SoftDeletes::class, class_uses($modelClass))) {
        $categories = $modelClass::withTrashed()->get();
    } else {
        $categories = $modelClass::get();
    }
    if (isset($relation)) {
        $CategoryIds = $relation::pluck($valueField)->toArray();
        $filteredCategories = $categories->filter(function ($category) use ($CategoryIds, $valueField) {
            return in_array($category->$valueField, $CategoryIds);
        });
        $data = $filteredCategories->values();
    } else {
        $data = $categories;
    }
    $dropdown = $data->map(function ($item) use ($valueField, $labelField) {
        return [
            'index' => $item->$valueField,
            'label' => $item->$labelField,
        ];
    })->toArray();

    return $addExtraOption ? array_merge([['index' => '', 'label' => __('common::crud.text.select_any')]], $dropdown) : $dropdown;
}
