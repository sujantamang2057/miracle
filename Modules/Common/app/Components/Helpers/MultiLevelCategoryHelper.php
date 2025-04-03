<?php

/**
 * Helper Class MultiLevelCategoryHelper
 */

namespace Modules\Common\app\Components\Helpers;

class MultiLevelCategoryHelper
{
    protected $model;

    protected $level;

    protected $mode;

    protected $moduleName;

    protected $controllerName;

    protected $actionName;

    protected $parentCatId;

    public function __construct($model, $level = null, $mode = null)
    {
        try {
            $this->model = ($model->exists) ? $model : new $model;
            $this->level = $level;
            $this->mode = $mode; // extra parameter for filter option in listing
            // model search
            $modelSearch = class_basename($this->model);
            $this->parentCatId = request()->input("{$modelSearch}.parent_category_id", '');
            $this->controllerName = getControllerName();
            $this->actionName = getActionName();
        } catch (ErrorException $e) {
            logError('MultiLevelCategoryHelper: ' . $e);   
        }
    }

    public function getMultiCategory($attribute, $label, $isRequired = false, $categoryId = null, $extraOption = true)
    {
        return $this->getCategoryRecursive($attribute, $label, $isRequired, $categoryId, $extraOption);
    }

    // category master
    public function getCategoryRecursive($attribute, $label, $isRequired, $categoryId, $extraOption)
    {
        try {
            $model = $this->model;

            $categories = $model::published()
                ->orderBy('show_order', 'desc')
                ->get();

            if (!empty($categoryId)) {
                $categories = $categories->where('category_id', $categoryId);
            }

            $groupedCategories = $categories->groupBy('parent_category_id');

            // Initialize tree data
            $treeCategoryData = [];

            // Iterate through parent categories
            foreach ($groupedCategories[null] as $parentCat) {
                $parentCategoryArray = [];
                $parentCatID = $parentCat->category_id;
                $tmpParentArray = [
                    'category_name' => $parentCat->category_name,
                    'parent_category_id' => $parentCat->parent_category_id,
                    '_data_level' => FIRST_CATEGORY_LEVEL,
                ];
                $parentCategoryArray['_data'] = $tmpParentArray;

                // Initialize child category array
                $childCategoryArray = [];

                // Check for child categories
                if (isset($groupedCategories[$parentCatID])) {
                    foreach ($groupedCategories[$parentCatID] as $childCat) {
                        $childCatID = $childCat->category_id;
                        $tmpChildArray = [
                            'category_name' => $childCat->category_name,
                            'parent_category_id' => $childCat->parent_category_id,
                            '_data_level' => SECOND_CATEGORY_LEVEL,
                        ];
                        $childCategoryArray[$childCatID]['_data'] = $tmpChildArray;

                        // Initialize grandchild category array
                        $grandChildCategoryArray = [];

                        // Check for grandchild categories
                        if (isset($groupedCategories[$childCatID])) {
                            foreach ($groupedCategories[$childCatID] as $grandChildCat) {
                                $grandChildCatID = $grandChildCat->category_id;
                                $tmpGrandChildArray = [
                                    'category_name' => $grandChildCat->category_name,
                                    'parent_category_id' => $grandChildCat->parent_category_id,
                                    '_data_level' => THIRD_CATEGORY_LEVEL,
                                ];
                                $grandChildCategoryArray[$grandChildCatID]['_data'] = $tmpGrandChildArray;
                            }

                            // Add grandchild data to child category
                            $childCategoryArray[$childCatID]['grand_child'] = $grandChildCategoryArray;
                        }

                        // Add child category data to parent category
                        $parentCategoryArray['child'] = $childCategoryArray;
                    }
                }

                // Add parent category data to tree
                $treeCategoryData['parent_category'][$parentCatID] = $parentCategoryArray;
            }

            if ($extraOption === true) {
                $treeCategoryData = array_merge(['' => 'Select Any'], $treeCategoryData);
            }

            // Return dropdown HTML
            return $this->__dropdownListHtml($attribute, $label, $isRequired, $treeCategoryData);
        } catch (\Exception $e) {
            logError('MultiLevelCategoryHelper: Category Recursive: ' . $e);
        }
    }

    // return category drop-down html
    private function __dropdownListHtml($attribute, $label, $isRequired = false, $optionList = [])
    {
        try {
            $parentClass = '';
            $childClass = '';
            $model = $this->model;
            $modelName = '';
            if ($this->controllerName == 'PostCategoryController') {
                $modelName = 'PostCategory';
            } elseif ($this->controllerName == 'PostController') {
                $modelName = 'Post';
                $attribute = 'category_id';
            } else {
                $modelName = '';
            }

            $controllerName = getControllerName();
            $actionName = getActionName();
            $commonAttribute = strtolower($modelName) . '-' . $attribute;
            $nameAttribute = $modelName . '[' . $attribute . ']';

            $html = '';
            if (in_array($actionName, ['create', 'edit'])) {
                $requiredClass = ($isRequired === true) ? ' required' : '';
                $html .= '<div class="form-group field-' . strtolower($modelName) . '-' . $attribute . $requiredClass . '">';
                $html .= '<label class="control-label" for="' . strtolower($modelName) . '-' . $attribute . '">' . $label . ': </label>';
            }
            $html .= '<select id="' . $commonAttribute . '" class="form-control" name="' . $nameAttribute . '" aria-invalid="false">';
            if (isset($optionList[''])) {
                $html .= '<option value="">' . $optionList[''] . '</option>';
            }
            // process parent category
            if (!empty($optionList['parent_category'])) {
                $parentDataTmp = [];
                foreach ($optionList['parent_category'] as $parentKey => $parentValue) {
                    $catId = $parentKey;
                    $parentData = isset($parentValue['_data']) ? $parentValue['_data'] : null;
                    if (!empty($parentData) || !empty($parentDataTmp)) {
                        $parentDataTmp = $parentData;
                    }
                    $managedCatData = $this->__manageCatData($parentKey, $parentDataTmp, FIRST_CATEGORY_LEVEL);
                    if (!isset($parentValue['child']) || empty($parentValue['child'])) {
                        $html .= $this->__manageInnerHTML($parentKey, $managedCatData, FIRST_CATEGORY_LEVEL, true);
                    } else {
                        $html .= $this->__manageInnerHTML($parentKey, $managedCatData, FIRST_CATEGORY_LEVEL, false);

                        $childDataTmp = [];
                        foreach ($parentValue['child'] as $childKey => $childValue) {
                            $childCatId = $childKey;
                            $childData = isset($childValue['_data']) ? $childValue['_data'] : null;
                            if (!empty($childData) || !empty($childDataTmp)) {
                                $childDataTmp = $childData;
                            }
                            //
                            $managedCatData = $this->__manageCatData($childKey, $childDataTmp, SECOND_CATEGORY_LEVEL);
                            if (!isset($childValue['grand_child']) || empty($childValue['grand_child'])) {
                                $html .= $this->__manageInnerHTML($childKey, $managedCatData, SECOND_CATEGORY_LEVEL, true);
                            } else {
                                $html .= $this->__manageInnerHTML($childKey, $managedCatData, SECOND_CATEGORY_LEVEL, false);
                                $grandChildDataTmp = [];
                                foreach ($childValue['grand_child'] as $grandChildKey => $grandChildValue) {
                                    $grandChildCatId = $grandChildKey;
                                    $grandChildData = isset($grandChildValue['_data']) ? $grandChildValue['_data'] : null;
                                    if (!empty($grandChildData) || !empty($grandChildDataTmp)) {
                                        $grandChildDataTmp = $grandChildData;
                                    }
                                    $managedCatData = $this->__manageCatData($grandChildKey, $grandChildDataTmp, THIRD_CATEGORY_LEVEL);
                                    $html .= $this->__manageInnerHTML($grandChildKey, $managedCatData, THIRD_CATEGORY_LEVEL, true);
                                }
                            }
                        }
                    }
                }
            }
            $html .= '</select>';
            if (in_array($actionName, ['create', 'edit'])) {
                $html .= '</div>';
            }
            $html .= '<style>.parent {background: #555; color:#fff; } .child {margin-left: 5px;} .grandchild {} </style>';

            return $html;
        } catch (ErrorException $e) {
            logError('MultiLevelCategoryHelper: DropDown issue: ' . $e);
        }
    }

    //
    private function __manageCatData($catId = null, $dataArray = [], $dataLevel = THIRD_CATEGORY_LEVEL)
    {
        $returnCatData = [];
        try {
            $dataToShow = $this->__dataToShow($catId, $dataLevel, $dataArray);
            if ($dataToShow === false) {
                return $returnCatData;
            }

            if (!empty($dataArray) && isset($dataArray['category_name'])) {
                $catName = $catNameFixed = $dataArray['category_name'];
                // template fix
                if ($dataLevel == SECOND_CATEGORY_LEVEL || $dataLevel == THIRD_CATEGORY_LEVEL) {
                    $catReplace = ($dataLevel == SECOND_CATEGORY_LEVEL) ? CATEGORY_TEMPLATE_CHAR_LEVEL_2 : CATEGORY_TEMPLATE_CHAR_LEVEL_3;
                    $catNameFixed = str_replace('&nbsp', ' ', html_entity_decode($catReplace . $catName));
                }
                // selected class
                if ($this->mode !== 'category') {
                    $isSelected = ($this->model->parent_category_id == $catId || $this->model->category_id == $catId || $this->parentCatId == $catId) ? 'selected="selected"' : '';
                } else {
                    $isSelected = ($this->model->parent_category_id == $catId || $this->parentCatId == $catId) ? 'selected="selected"' : '';
                }
                // fill-up array
                $returnCatData['category_name'] = $catNameFixed;
                $returnCatData['is_selected'] = $isSelected;
            }

            return $returnCatData;
        } catch (ErrorException $e) {
            logError('MultiLevelCategoryHelper: Manage Category Data issue: ' . $e);
        }
    }

    //
    private function __manageInnerHTML($catId = null, $dataArray = [], $dataLevel = THIRD_CATEGORY_LEVEL, $isLastItem = false, $class = '')
    {
        $returnHtml = '';
        try {
            if (!isset($dataArray['category_name'])) {
                return $returnHtml;
            }
            if (isset($dataArray['category_name'])) {
                $catName = $dataArray['category_name'];
                $isSelected = $dataArray['is_selected'];
                //
                $optgroupHtml = '<optgroup class="' . $class . '" label="' . $catName . ' " ' . $isSelected . '></optgroup>';
                $optionHtml = '<option class="' . $class . '" value="' . $catId . '" ' . $isSelected . '>' . $catName . ' </option>';
                $dataSelectable = $this->__dataSelectable($catId, $dataLevel, $isLastItem);

                if ($dataSelectable === true) {
                    $returnHtml = $optionHtml;
                } else {
                    $returnHtml = $optgroupHtml;
                }
            }

            return $returnHtml;
        } catch (ErrorException $e) {
            logError('MultiLevelCategoryHelper: InnerHtml issue: ' . $e);
        }
    }

    // check data to show or not
    protected function __dataToShow($catId = null, $dataLevel = null, $dataArray = null)
    {
        $showData = false;
        try {
            $constantPreName = class_basename(get_class($this->model));
            $constantShowable = null;

            $categoryShowableConstants = [
                'PostCategory' => POST_CATEGORY_SHOWABLE,
            ];
            if (isset($categoryShowableConstants[$constantPreName])) {
                $constantShowable = $categoryShowableConstants[$constantPreName];
            }

            // Fail-safe check
            if (is_null($constantShowable)) {
                return $showData;
            }

            // Fetch related config/constants
            $showableRule = json_decode($constantShowable, true);
            $tmpRule = isset($showableRule[$dataLevel]) ? $showableRule[$dataLevel] : [];
            foreach ($tmpRule as $tmpController => $tmpActions) {
                if ($this->controllerName == $tmpController && in_array($this->actionName, $tmpActions)) {
                    $showData = true;
                }
            }

            // Do not show Self & Child news-category update page
            $updateCatId = $this->model->category_id;
            $_dataLevel = $dataArray['_data_level'] ?? null;
            $parentCatId = $dataArray['parent_category_id'] ?? null;

            if (in_array($this->controllerName, ['PostCategoryController']) &&
                $this->actionName === 'edit' &&
                in_array($updateCatId, [$catId, $parentCatId])) {
                // Do not show self-data & 3rd level data in news-category edit pages
                $showData = false;
            }

            return $showData;
        } catch (\Exception $e) {
            logError('MultiLevelCategoryHelper: Data Showable issue: ' . $e);
        }
    }

    protected function __dataSelectable($catId = null, $dataLevel = null, $isLastItem = null)
    {
        $selectable = false;
        try {
            $constantPreName = class_basename($this->model);
            if (in_array($constantPreName, ['PostCategory'])) {
                $constantSelectable = POST_CATEGORY_SELECTABLE;
            }
            if (empty($constantSelectable)) {
                return $selectable;
            }
            // fetch related config/constants
            $selectableRule = json_decode($constantSelectable, true);
            $tmpRule = isset($selectableRule[$dataLevel]) ? $selectableRule[$dataLevel] : [];
            foreach ($tmpRule as $tmpController => $tmpActions) {
                if ($this->controllerName == $tmpController && in_array($this->actionName, $tmpActions)) {
                    $selectable = true;
                }
            }
            // overide
            if (in_array($this->controllerName, ['PostCategoryController'])) {
                if ($dataLevel == THIRD_CATEGORY_LEVEL) {
                    $selectable = false;
                } elseif ($this->actionName == 'edit' && $catId == $this->model->category_id
                ) {
                    // Do not allow to select self in news-category update page
                    $selectable = false;
                }
            } elseif (in_array($this->controllerName, ['PostController'])) {
                if ($isLastItem === false) {
                    $selectable = false;
                }
            }

            return $selectable;
        } catch (ErrorException $e) {
            logError('MultiLevelCategoryHelper: Data Selectable issue: ' . $e);
        }
    }
}
