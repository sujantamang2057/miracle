<?php

namespace Modules\Tools\app\Http\Controllers;

use Carbon\Carbon;
use File;
use Illuminate\Http\Request;

class ImageCleanerController extends ToolsController
{
    public function __construct()
    {

        $this->moduleName = 'tools.imageCleaner';
        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'cleanTemporaryFiles']) => ['index'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['cleanTemporaryFiles']) => ['cleanTemporaryFiles'],
        ];
        // Used middleware using the helper function Using role and permission
        applyMiddlewareWithRole($this, $middlewareMap, HAS_ANY_ROLE_MASTER_OR_ADMIN);
    }

    public function index()
    {
        return view('tools::image_cleaner.index');
    }

    public function cleanTemporaryFiles(Request $request)
    {
        if ($request->ajax()) {
            $returnHtml = $this->__cleanFiles(storage_path(UPLOAD_FILE_DIR_NAME_TMP), $request->input('days_old'));

            return response()->json(['html' => $returnHtml]);
        }

        return redirect(route('tools.imageCleaner.index'));
    }

    private function __cleanFiles($fileDir, $daysOld)
    {
        $daysOld = !empty($daysOld) ? $daysOld : 30;
        $cleanHtml = '';
        if (!empty($fileDir) && File::isDirectory($fileDir)) {
            $directories = File::directories($fileDir);
            $backUpClearTime = Carbon::now()->subDays($daysOld)->timestamp;
            $filesList = $this->__getFilesList($fileDir);
            $filesToBeDeleted = [];
            $count = 0;
            if (!empty($filesList)) {
                foreach ($filesList as $key => $file) {
                    if ($backUpClearTime > $file->getMTime()) {
                        File::delete($file);
                        $count++;
                    }
                }
            }
        }

        $returnHtml = empty($count) ? "<div class='callout callout-danger'><h6 class='mb-0'>" . __('tools::image_cleaners.message.no_temp_image_found', ['days' => $daysOld]) . '</h6></div>' : "<div class='callout callout-success'><h6 class='mb-0'>" . __('tools::image_cleaners.message.temp_image_deleted', ['count' => $count, 'days' => $daysOld]) . '</h6></div>';

        return $returnHtml;
    }

    private function __getFilesList($dirName)
    {
        if (empty($dirName)) {
            return [];
        }
        $filesList = [];
        if (File::isDirectory($dirName)) {
            $filesList = File::allFiles($dirName);
        }

        return $filesList;
    }

    private function __getLastModifiedDate($path, $formatted = true)
    {
        if (empty($path)) {
            return '';
        }
        $lastModifiedDate = File::lastModified($path);

        return $formatted ? date(DEFAULT_DATETIME_FORMAT, $lastModifiedDate) : $lastModifiedDate;
    }
}
