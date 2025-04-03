<?php

namespace Modules\Common\app\Components;

class RegenerateImageUtility
{
    public static function regenerateImages($fieldID = null, $fieldImage = null, $modelName = null, $id = null, $imageConfigArray = null, $offset = 0, $limit = IMAGE_REGENERATOR_DATA_FETCH_LIMIT, $moduleName = null)
    {
        $saveMsg = [];
        $modelInstance = new $modelName;
        $condition = [];
        if ($modelInstance->hasAttribute('publish')) {
            $condition = ['publish' => 1];
        }
        if (!empty($id)) {
            $condition[$fieldID] = $id;
        }
        $allData = $modelName::where($condition)->limit($limit)->offset($offset)->get();
        $filteredData = [];
        if (!empty($allData)) {
            $filteredData = $allData->mapWithKeys(function ($item, $key) use ($fieldID, $fieldImage) {
                return [$item[$fieldID] => $item[$fieldImage]];
            });
        }

        if (!empty($filteredData)) {
            foreach ($filteredData as $key => $imageName) {
                if ($imageName) {
                    $return = self::__regenerateImages($key, $modelName, $imageName, $imageConfigArray, $moduleName);
                } else {
                    $return = __('tools::image_regenerators.messages.image_missing_database', ['id' => $key]);
                }
                $saveMsg[$key] = $return;
            }
        }

        unset($allData, $filteredData);

        return $saveMsg;
    }

    private static function __regenerateImages($id = null, $modelName = null, $imageName = null, $imageConfigArray = null, $moduleName = null)
    {
        if (empty($id) || empty($imageName) || empty($imageConfigArray)) {
            return 'Empty Post ID!';
        }
        // get value from array
        $imageDir = isset($imageConfigArray['imageDir']) ? $imageConfigArray['imageDir'] : null;
        $imageSizes = isset($imageConfigArray['imageSizes']) ? $imageConfigArray['imageSizes'] : null;
        $imagesKeyword = isset($imageConfigArray['imagesKeyword']) ? $imageConfigArray['imagesKeyword'] : null;

        if (!empty($imageDir) && !empty($imageSizes)) {
            $return = ImageUploadManager::resizeImage($imageName, $imageDir, $imageSizes);

            if ($return) {
                return __('tools::image_regenerators.messages.image_regenerated', ['id' => $id, 'fileName' => $imageName, 'sizes' => $imageSizes]);
            }

            return __('tools::image_regenerators.messages.image_missing', ['id' => $id, 'fileName' => $imageName]);
        }

        return __('tools::image_regenerators.messages.image_missing', ['id' => $id, 'fileName' => $imageName]);
    }
}
