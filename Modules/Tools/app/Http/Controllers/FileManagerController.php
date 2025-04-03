<?php

namespace Modules\Tools\app\Http\Controllers;

use Barryvdh\Elfinder\ElfinderController;
use Carbon\Carbon;
use File;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Image;

class FileManagerController extends ElfinderController
{
    public $moduleName;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->moduleName = 'tools.filemanager';
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'uploader']) => ['index'],
        ];
        // Used middleware using the helper function Using role and permission
        applyMiddlewareWithRole($this, $middlewareMap, HAS_ANY_ROLE_MASTER_OR_ADMIN);
    }

    public function index()
    {
        return view('tools::file_manager.index')
            ->with($this->getViewVars());
    }

    // TinyMCE: Image Upload Handler
    public function uploader(Request $request)
    {
        $file = $request->file('file');
        $imageFileName = null;
        $dirName = UPLOAD_FILE_DIR_NAME;
        $uploadImageFilePath = storage_path(UPLOAD_FILE_PATH);
        if (!empty($file)) {
            // Check & CreateDir
            File::ensureDirectoryExists($uploadImageFilePath);

            // Use unique filename
            $imageFileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            // Upload Original File
            Image::read($file)->save($uploadImageFilePath . DS . $imageFileName);
        }
        $url = asset('storage/' . $dirName . '/' . $imageFileName);

        return response()->json(['location' => $url]);
    }
}
