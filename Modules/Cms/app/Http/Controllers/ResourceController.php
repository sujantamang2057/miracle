<?php

namespace Modules\Cms\app\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Cms\app\Components\Helpers\ResourceHelper;
use Modules\CmsAdmin\app\Models\Resource;
use Modules\Common\app\Http\Controllers\FrontendController;

class ResourceController extends FrontendController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filterDate = $request->input('year');
        $resourceData = ResourceHelper::getResourceList(true, null, $filterDate);

        if ($request->ajax()) {
            return view('cms::resources.resource_list', compact('resourceData'));
        }

        return view('cms::resources.index')
            ->with('resourceData', $resourceData);
    }

    public function downloadCount(Request $request)
    {
        $resource = Resource::find($request->id);

        if ($resource && file_exists(storage_path('app/public/' . RESOURCE_FILE_DIR_NAME . '/' . $resource->file_name))) {
            $resource->increment('download_count');

            return response()->json([
                'success' => true,
            ]);
        } else {
            $data = [
                'message' => __('cms::general.file_not_found'),
                'success' => 0,
            ];

            return response()->json($data);
        }
    }
}
