<?php

namespace Modules\Common\app\Components;

use Carbon\Carbon;
use File;
use Image;

class ImageUploadManager
{
    /**
     * process uploaded image
     */
    public static function processUploadedImage($fileName = null, $uploadImageFilePathTmp = null, $imageDimensionsTmp = null)
    {
        $imageFileName = null;
        $uploadImageFilePath = empty($uploadImageFilePathTmp) ? storage_path(UPLOAD_FILE_PATH) : $uploadImageFilePathTmp;
        $imageDimensions = empty($imageDimensionsTmp) ? json_decode(UPLOAD_FILE_DIMENSIONS, true) : $imageDimensionsTmp;

        if (!empty($fileName)) {
            $tmpFileName = storage_path(UPLOAD_FILE_DIR_NAME_TMP . DS . $fileName);
            $tmpFileExt = pathinfo($fileName, PATHINFO_EXTENSION);

            // Check & CreateDir
            File::ensureDirectoryExists($uploadImageFilePath);

            // Use unique filename
            $imageFileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $tmpFileExt;

            if (in_array($tmpFileExt, NON_RESIZABLE_TYPES)) {
                File::copy($tmpFileName, $uploadImageFilePath . DS . $imageFileName);
            } else {
                $tmpFile = Image::read($tmpFileName);
                // Upload Original File
                $tmpFile->save($uploadImageFilePath . DS . $imageFileName);
            }
            self::__copyFiles($tmpFileName, $tmpFileExt, $imageDimensions, $uploadImageFilePath, $imageFileName);

            // if (file_exists($tmpFileName)) {
            //     File::delete($tmpFileName);
            // }

        }

        return $imageFileName;
    }

    public static function resizeImage($fileName, $imageFilePath, $imageDimensions)
    {
        if (empty($fileName) || empty($imageFilePath) || empty($imageDimensions)) {
            return false;
        }
        $imageFilePath = storage_path($imageFilePath);
        $imageDimensions = json_decode($imageDimensions, true);

        // Check & CreateDir
        File::ensureDirectoryExists($imageFilePath);

        $currentFileName = $imageFilePath . DS . $fileName;
        $currentFileExt = pathinfo($currentFileName, PATHINFO_EXTENSION);

        if (file_exists($currentFileName)) {
            self::__copyFiles($currentFileName, $currentFileExt, $imageDimensions, $imageFilePath, $fileName);

            return true;
        }

        return false;

    }

    private static function __copyFiles($currentFilePath, $currentFileExt, $imageDimensions, $filePath, $fileName)
    {
        foreach ($imageDimensions as $row) {
            // Check & Create Sub-Folder
            $imageFilePathSub = $filePath . DS . $row;
            File::ensureDirectoryExists($imageFilePathSub);

            $saveFilePath = $imageFilePathSub . DS . $fileName;

            if (in_array($currentFileExt, NON_RESIZABLE_TYPES)) {
                File::copy($currentFilePath, $saveFilePath);
            } else {
                // Resize Image maintaining aspect ratio
                $resizeImage = Image::read($currentFilePath)->scaleDown($row);
                $resizeImage->save($saveFilePath);
            }
        }
    }
}
