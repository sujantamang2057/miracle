<?php

namespace Modules\Tools\app\Http\Controllers;

use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BackupCleanerController extends ToolsController
{
    public function __construct()
    {

        $this->moduleName = 'tools.backupCleaner';
        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'cleanBlocks', 'cleanPages', 'cleanEmailTemplates']) => ['index'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['cleanBlocks']) => ['cleanBlocks'],
            buildCanMiddleware($this->moduleName, ['cleanPages']) => ['cleanPages'],
            buildCanMiddleware($this->moduleName, ['cleanEmailTemplates']) => ['cleanEmailTemplates'],
        ];
        // Used middleware using the helper function Using role and permission
        applyMiddlewareWithRole($this, $middlewareMap, HAS_ANY_ROLE_MASTER_OR_ADMIN);
    }

    public function index()
    {
        return view('tools::backup_cleaner.index');
    }

    public function cleanBlocks(Request $request)
    {
        if ($request->ajax()) {
            $returnHtml = $this->__cleanFiles(resource_path('views' . DS . 'components' . DS . 'blocks'));

            return response()->json(['html' => $returnHtml]);
        }

        return redirect(route('tools.backupCleaner.index'));
    }

    public function cleanPages(Request $request)
    {
        if ($request->ajax()) {
            $returnHtml = $this->__cleanFiles(resource_path('views' . DS . 'components' . DS . 'pages_dynamic'));

            return response()->json(['html' => $returnHtml]);
        }

        return redirect(route('tools.backupCleaner.index'));
    }

    public function cleanEmailTemplates(Request $request)
    {
        if ($request->ajax()) {
            $returnHtml = $this->__cleanFiles(resource_path('views' . DS . 'components' . DS . 'emailTemplates'));

            return response()->json(['html' => $returnHtml]);
        }

        return redirect(route('tools.backupCleaner.index'));
    }

    private function __cleanFiles($fileDir)
    {
        $cleanHtml = '';
        if (!empty($fileDir) && File::isDirectory($fileDir)) {
            $directories = File::directories($fileDir);
            $backUpClearTime = Carbon::now()->subDays(BACKUP_CLEAR_DAYS)->timestamp;
            if (!empty($directories)) {
                Arr::where($directories, function ($dir, $key) use ($backUpClearTime, &$cleanHtml) {
                    $dirName = File::name($dir);
                    if ($backUpClearTime > $this->__getLastModifiedDate($dir, false) && Str::startsWith($dirName, '_backup')) {
                        $filesList = $this->__getFilesList($dir);
                        if (!empty($filesList)) {
                            foreach ($filesList as $fileKey => $file) {
                                if ($backUpClearTime > $this->__getLastModifiedDate($file, false)) {
                                    $cleanHtml .= '<div class="callout callout-success"><span class="d-block h6">' . __('tools::backup_cleaners.text.removed_file', ['file' => File::name($file)]) . '</span>' . $file . '</div>' . EOL;
                                    File::delete($file);
                                }
                            }
                            if (File::isEmptyDirectory($dir)) {
                                $cleanHtml .= '<div class="callout callout-success"><span class="d-block h6">' . __('tools::backup_cleaners.text.removed_directory', ['directory' => $dirName]) . '</span>' . $dir . '</div>' . EOL;
                                File::deleteDirectory($dir);
                            }
                        } else {
                            $cleanHtml .= '<div class="callout callout-success"><span class="d-block h6">' . __('tools::backup_cleaners.text.removed_directory', ['directory' => $dirName]) . '</span>' . $dir . '</div>' . EOL;
                            File::deleteDirectory($dir);
                        }
                    }
                });
            }
        }
        $returnHtml = "<div class='callout callout-info'><h6 class='mb-0'>" . __('tools::backup_cleaners.text.cleaning_start', ['name' => Str::of(File::name($fileDir))->camel()->snake(' ')->title()]) . '</h6></div>' . EOL;
        $returnHtml .= empty($cleanHtml) ? "<div class='callout callout-warning'><h6 class='mb-0'>" . __('tools::backup_cleaners.text.nothing_to_clear') . '</h6></div>' : $cleanHtml;
        $returnHtml .= "<div class='callout callout-info'><h6 class='mb-0'>" . __('tools::backup_cleaners.text.cleaning_end') . '</h6></div>' . EOL;

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
