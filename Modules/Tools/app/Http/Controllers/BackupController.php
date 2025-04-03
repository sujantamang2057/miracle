<?php

namespace Modules\Tools\app\Http\Controllers;

use Artisan;
use Carbon\Carbon;
use Exception;
use Flash;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Log;
use Request;
use Storage;

class BackupController extends ToolsController
{
    protected $backups = [];

    public function __construct()
    {

        $this->moduleName = 'tools.backup';
        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'delete', 'bulkDelete', 'download']) => ['index'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['create']) => ['create'],
            buildCanMiddleware($this->moduleName, ['delete']) => ['delete'],
            buildCanMiddleware($this->moduleName, ['bulkDelete']) => ['bulkDelete'],
            buildCanMiddleware($this->moduleName, ['download']) => ['download'],
        ];
        // Used middleware using the helper function Using role and permission
        applyMiddlewareWithRole($this, $middlewareMap, HAS_ANY_ROLE_MASTER_OR_ADMIN);
    }

    public function index()
    {
        if (!count(config('backup.backup.destination.disks'))) {
            abort(500, __('tools::backups.no_disks_configured'));
        }

        foreach (config('backup.backup.destination.disks') as $diskName) {
            $disk = Storage::disk($diskName);
            $files = $disk->allFiles();

            // make an array of backup files, with their filesize and creation date
            foreach ($files as $file) {
                // remove diskname from filename
                $fileName = str_replace('backups/', '', $file);
                $downloadLink = route('tools.backup.download', ['file_name' => $fileName, 'disk' => $diskName]);
                $deleteLink = route('tools.backup.delete', ['file_name' => $fileName, 'disk' => $diskName]);

                // only take the zip files into account
                if (substr($file, -4) == '.zip' && $disk->exists($file)) {

                    // some workouts here
                    // get filename only
                    $filePathArray = explode('/', $file);
                    $filePathArrayRev = array_reverse($filePathArray);
                    $fileNameOnly = (isset($filePathArrayRev[0])) ? $filePathArrayRev[0] : $disk->lastModified($f);
                    // END

                    $this->backups[] = (object) [
                        'filePath' => $file,
                        'fileName' => $fileName,
                        'fileSize' => bytesToHuman($disk->size($file)),
                        'lastModified' => Carbon::createFromTimeStamp($disk->lastModified($file))->formatLocalized('%d %B %Y, %H:%M'),
                        'diskName' => $diskName,
                        'downloadLink' => is_a($disk->getAdapter(), LocalFilesystemAdapter::class, true) ? $downloadLink : null,
                        'deleteLink' => $deleteLink,
                        'fileNameOnly' => $fileNameOnly,
                        'fileNameWithPath' => str_replace('backups/', '', $file),
                    ];
                }
            }
        }

        // reverse the backups, so the newest one would be on top
        $this->backups = array_reverse($this->backups);

        return view('tools::backup.index')
            ->with('backups', $this->backups);
    }

    public function create()
    {
        try {
            ini_set('max_execution_time', 600);

            Log::info('BackupManager -- Called backup:run from admin interface');

            // Artisan::call('backup:run');
            Artisan::call('backup:run --only-db --disable-notifications');

            Log::info('BackupManager -- backup process has started');

            $output = Artisan::output();
            if (strpos($output, 'Backup failed because')) {
                preg_match('/Backup failed because(.*?)$/ms', $output, $match);
                $message = 'BackupManager -- backup process failed because ' . ($match[1] ?? '');
                Log::error($message . PHP_EOL . $output);
                Flash::error($message)->important();
            } else {
                Flash::success(__('common::messages.saved', ['model' => __('tools::backups.backup')]))->important();
            }
        } catch (Exception $e) {
            // Response::make($e->getMessage(), 500);

            Log::error('BackupController: Create issue: ' . $e);
            Flash::error($e->getMessage())->important();
        }

        return redirect()->route('tools.backup.index');
    }

    /**
     * Downloads a backup zip file.
     */
    public function download()
    {
        $diskName = Request::input('disk');
        $fileName = Request::input('file_name');
        $disk = Storage::disk($diskName);

        if (!$this->isBackupDisk($diskName)) {
            abort(500, __('tools::backups.unknown_disk'));
        }

        if (!is_a($disk->getAdapter(), LocalFilesystemAdapter::class, true)) {
            abort(404, __('tools::backups.only_local_downloads_supported'));
        }

        if (!$disk->exists($fileName)) {
            abort(404, __('tools::backups.backup_doesnt_exist'));
        }

        return $disk->download($fileName);
    }

    /**
     * Deletes a backup file.
     */
    public function delete()
    {
        $diskName = Request::input('disk');
        $fileName = Request::input('file_name');

        if (!$this->isBackupDisk($diskName)) {
            return response(__('tools::backups.unknown_disk'), 500);
        }

        $disk = Storage::disk($diskName);

        if (!$disk->exists($fileName)) {
            return response(__('tools::backups.backup_doesnt_exist'), 404);
        }

        $disk->delete($fileName);
        Flash::success(__('common::messages.deleted', ['model' => __('tools::backups.backup')]))->important();

        return redirect()->route('tools.backup.index');
    }

    /**
     * BulkDelete a backup file.
     */
    public function bulkDelete()
    {
        $diskName = Request::input('disk');
        if (!$this->isBackupDisk($diskName)) {
            return response(__('tools::backups.unknown_disk'), 500);
        }

        $disk = Storage::disk($diskName);
        $files = $disk->allFiles(env('APP_NAME'));

        $disk->delete($files);

        return redirect()->route('tools.backup.index');
    }

    /**
     * Check if disk is a backup disk.
     *
     *
     * @return bool
     */
    private function isBackupDisk(string $diskName)
    {
        return in_array($diskName, config('backup.backup.destination.disks'));
    }
}
