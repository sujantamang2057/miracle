<?php

namespace Modules\Common\app\Components;

use Carbon\Carbon;
use File;

class FileUploadManager
{
    /**
     * Process uploaded file
     */
    public static function processUploadedFile($fileName = null, $uploadFilePathTmp = null)
    {
        $processedFileData = [
            'name' => null,
            'type' => null,
            'size' => null,
        ];
        $uploadFilePath = empty($uploadFilePathTmp) ? storage_path(UPLOAD_FILE_PATH) : $uploadFilePathTmp;

        if (!empty($fileName)) {
            $tmpFileName = storage_path(UPLOAD_FILE_DIR_NAME_TMP . DS . $fileName);
            $tmpFileExt = pathinfo($fileName, PATHINFO_EXTENSION);

            // Check & CreateDir
            File::ensureDirectoryExists($uploadFilePath);

            // Use unique filename
            $processedFileData['name'] = $processedFileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $tmpFileExt;
            $processedFileData['type'] = $tmpFileExt;
            $processedFileData['size'] = bytesToHuman(File::size($tmpFileName));
            // Upload Original File
            File::move($tmpFileName, $uploadFilePath . DS . $processedFileName);
        }

        return $processedFileData;
    }
}
