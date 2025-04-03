<?php

namespace Modules\Tools\app\Http\Controllers;

use Illuminate\Support\Facades\File;
use Modules\Common\app\Http\Controllers\BackendController;

class SpaceReportController extends BackendController
{
    public function __construct()
    {
        $this->moduleName = 'tools.spaceReport';
        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['index']) => ['index'],
        ];
        // Used middleware using the helper function Using role and permission
        applyMiddlewareWithRole($this, $middlewareMap, HAS_ANY_ROLE_MASTER_OR_ADMIN);
    }

    public function index()
    {
        $data = [];
        $spaceReportPathArray = SPACE_REPORT_PATH_ARRAY;
        $totalSize = 0;
        foreach ($spaceReportPathArray as $key => $item) {
            $size = $this->__getDirectorySize($item['path'], $item['path'], in_array($key, BACKUP_FOLDER_KEY));
            $size = $this->convertByteToMB($size);
            $size = round($size, 2);
            $totalSize += $size;

            $data[$key] = [
                'size' => $size,
                'name' => __('tools::space_report.' . $item['name_key']),
                'bg_color' => $item['bg_color'],
                'color' => $item['color'],
            ];
        }

        return view('tools::space_report.index', ['totalSize' => $totalSize, 'data' => $data]);
    }

    private function __getDirectorySize($path, $folderPath, $checkBackupFileOnly = false)
    {
        $totalSize = 0;
        if (is_dir($path)) {
            $files = File::allFiles($path);
            if (!empty($files)) {
                foreach ($files as $file) {
                    if ($file->isFile()) {
                        $addSize = true;
                        if ($checkBackupFileOnly) {
                            $addSize = $this->__isBackupFile($file, $folderPath);
                        }
                        $totalSize += $addSize ? $file->getSize() : 0;
                    } elseif ($file->isDir()) {
                        $totalSize += $this->__getDirectorySize($file, $folderPath, $checkBackupFileOnly);
                    }
                }
            }
        }

        return $totalSize;
    }

    private function convertByteToMB($size)
    {
        if (empty($size)) {
            return 0;
        }

        return $size / (1024 * 1024);
    }

    private function __isBackupFile($filePath, $folderPath)
    {
        if (empty($filePath) || empty($folderPath)) {
            return false;
        }

        $filePath = str_replace('\\', '/', $filePath);
        $folderPath = str_replace('\\', '/', $folderPath);

        $relPath = str_replace($folderPath, '', $filePath);

        return strpos($relPath, '_backup_') === 0;
    }
}
