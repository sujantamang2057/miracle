<?php

namespace Modules\Tools\app\Http\Controllers;

use Artisan;
use Exception;
use Flash;
use Log;

class CleanerController extends ToolsController
{
    public $cleanupMsg = '';

    public $cmdLists = [
        'cache:clear',
        'config:clear',
        'debugbar:clear ',
        'event:clear',
        'log-viewer:clear',
        'optimize:clear',
        'permission:cache-reset',
        'queue:clear',
        'route:clear',
        'schedule:clear-cache',
        'view:clear',
        'permission:cache-reset',
    ];

    public function __construct()
    {

        $this->moduleName = 'tools.cleaner';
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'cache', 'config', 'view', 'route', 'optimize', 'permissionCache']) => ['index'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['cache']) => ['cache'],
            buildCanMiddleware($this->moduleName, ['config']) => ['config'],
            buildCanMiddleware($this->moduleName, ['views']) => ['views'],
            buildCanMiddleware($this->moduleName, ['route']) => ['route'],
            buildCanMiddleware($this->moduleName, ['optimize']) => ['optimize'],
            buildCanMiddleware($this->moduleName, ['permissionCache']) => ['permissionCache'],
        ];
        // Used middleware using the helper function Using role and permission
        applyMiddlewareWithRole($this, $middlewareMap, HAS_ANY_ROLE_MASTER_OR_ADMIN);
    }

    public function index()
    {
        return view('tools::cleaner.index');
    }

    public function cache()
    {
        $this->__clear('cache:clear');

        $this->cleanupMsg = __('tools::cleaners.clear_cache_success');
        Flash::success($this->cleanupMsg)->important();

        return redirect(route('tools.cleaner.index'));
    }

    public function config()
    {
        $this->__clear('config:clear');

        $this->cleanupMsg = __('tools::cleaners.clear_config_success');
        Flash::success($this->cleanupMsg)->important();

        return redirect(route('tools.cleaner.index'));
    }

    public function views()
    {
        $this->__clear('view:clear');

        $this->cleanupMsg = __('tools::cleaners.clear_views_success');
        Flash::success($this->cleanupMsg)->important();

        return redirect(route('tools.cleaner.index'));
    }

    public function route()
    {
        $this->__clear('route:clear');

        $this->cleanupMsg = __('tools::cleaners.clear_route_success');
        Flash::success($this->cleanupMsg)->important();

        return redirect(route('tools.cleaner.index'));
    }

    public function optimize()
    {
        $this->__clear('optimize:clear');

        $this->cleanupMsg = __('tools::cleaners.clear_optimize_success');
        Flash::success($this->cleanupMsg)->important();

        return redirect(route('tools.cleaner.index'));
    }

    public function permissionCache()
    {
        $this->__clear('permission:cache-reset');

        $this->cleanupMsg = __('tools::cleaners.clear_permission_cache_success');
        Flash::success($this->cleanupMsg)->important();

        return redirect(route('tools.cleaner.index'));
    }

    private function __clear($cmd)
    {
        try {
            ini_set('max_execution_time', 600);

            Log::info('Cleaner -- Called from admin interface');

            Artisan::call($cmd);

            Log::info('Cleaner -- process has started');

            $output = Artisan::output();
            if (strpos($output, 'Cleaner failed because')) {
                preg_match('/Cleaner failed because(.*?)$/ms', $output, $match);
                $message = 'Cleaner ' . $cmd . '-- process failed because ' . ($match[1] ?? '');
                Log::error($message . PHP_EOL . $output);

                return response($message, 500);
            }
            Log::info('Cleaner -- process has completed');
        } catch (Exception $e) {
            Log::error('CleanerController: clear issue :' . $e);

            return response($e->getMessage(), 500);
        }
    }
}
