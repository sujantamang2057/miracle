<?php

namespace Modules\Common\app\Components;

use File;

class FileStorageManager
{
    /**
     * delete image files
     */
    public static function deleteImageFile($imageFileName = null, $imageFilePath = null, $imageDimensions = null)
    {
        if (!empty($imageFileName) && !empty($imageFilePath)) {
            // Check Dir
            if (!File::isDirectory($imageFilePath)) {
                return false;
            }

            // Delete Original File
            File::delete($imageFilePath . DS . $imageFileName);

            // Loop to delete multiple dimensions files as well
            foreach ($imageDimensions as $row) {
                // Check & Delete Files
                $imageFilePathSub = $imageFilePath . DS . $row;
                if (File::isDirectory($imageFilePathSub)) {
                    File::delete($imageFilePathSub . DS . $imageFileName);
                }
            }

            return true;
        }

        return false;
    }

    /**
     * create file with contents
     */
    public static function createFileWithContents($fileName = null, $filePath = null, $fileContents = null)
    {
        if (!empty($fileName) && !empty($fileContents)) {
            // Check & CreateDir
            if (!File::isDirectory($filePath)) {
                File::makeDirectory($filePath, PERMISSION_DIR);
            }
            // create file with contents
            $fileFullPath = $filePath . DS . $fileName;
            File::put($fileFullPath, $fileContents);

            return true;
        }

        return false;
    }

    /**
     * delete file
     */
    public static function deleteFile($fileName = null, $filePath = null)
    {
        if (!empty($fileName) && !empty($filePath)) {
            // Check Dir
            if (!File::isDirectory($filePath)) {
                return false;
            }
            // Delete File
            File::delete($filePath . DS . $fileName);

            return true;
        }

        return false;
    }
}
