<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\CmsAdmin\app\DataTables\VideoAlbumDataTable;
use Modules\CmsAdmin\app\DataTables\VideoAlbumTrashDataTable;
use Modules\CmsAdmin\app\Models\VideoAlbum;
use Modules\CmsAdmin\app\Repositories\VideoAlbumRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class VideoAlbumController extends BackendController
{
    public function __construct(VideoAlbumRepository $videoAlbumRepo)
    {
        $this->moduleName = 'cmsadmin.videoAlbums';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'restore', 'togglePublish', 'toggleReserved', 'trashList']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['toggleReserved']) => ['toggleReserved'],
            buildCanMiddleware($this->moduleName, ['trashList']) => ['trashList'],
            buildCanMiddleware($this->moduleName, ['restore']) => ['restore'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->langFile = 'cmsadmin::models/video_albums';
        $this->repository = $videoAlbumRepo;
        // define route for redirect
        $this->listRoute = route('cmsadmin.videoAlbums.index');
        $this->trashListRoute = route('cmsadmin.videoAlbums.trashList');
        $this->detailRouteName = 'cmsadmin.videoAlbums.show';
    }

    /**
     * Display a listing of the VideoAlbum.
     */
    public function index(VideoAlbumDataTable $videoAlbumDataTable)
    {
        return $videoAlbumDataTable->render('cmsadmin::video_albums.index');
    }

    /**
     * Display a listing of the Trashed VideoAlbum.
     */
    public function trashList(VideoAlbumTrashDataTable $videoAlbumTrashDataTable)
    {
        return $videoAlbumTrashDataTable->render('cmsadmin::video_albums.trash');
    }

    /**
     * Show the form for creating a new VideoAlbum.
     */
    public function create()
    {
        return view('cmsadmin::video_albums.create')
            ->with('id', null)
            ->with('publish', getOldData('publish'))
            ->with('reserved', getOldData('reserved'));
    }

    /**
     * Store a newly created VideoAlbum in storage.
     */
    public function store(Request $request)
    {
        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request);

        $input = $request->all();

        $videoAlbum = $this->repository->create($input);

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.videoAlbums.show', ['video_album' => $videoAlbum->album_id]));
    }

    /**
     * Display the specified VideoAlbum.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);
        if ($mode == 'trash-restore') {
            if (!$videoAlbum = VideoAlbum::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($videoAlbum->trashed()) {
                return view('cmsadmin::video_albums.show-trash')
                    ->with('videoAlbum', $videoAlbum)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('cmsadmin.videoAlbums.show', ['video_album' => $videoAlbum->album_id]));
            }
        } else {
            if (!$videoAlbum = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('cmsadmin::video_albums.show')
            ->with('videoAlbum', $videoAlbum)
            ->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified VideoAlbum.
     */
    public function edit($id)
    {
        if (!$videoAlbum = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('cmsadmin::video_albums.edit')
            ->with('id', $videoAlbum->album_id)
            ->with('publish', getOldData('publish', $videoAlbum->publish))
            ->with('reserved', getOldData('reserved', $videoAlbum->reserved))
            ->with('videoAlbum', $videoAlbum);
    }

    /**
     * Update the specified VideoAlbum in storage.
     */
    public function update($id, Request $request)
    {

        if (!$videoAlbum = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request, $id);

        $videoAlbum = $this->repository->update($request->all(), $id);

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.videoAlbums.show', $id));
    }

    // sanitize inputs
    private function __sanitize($request)
    {
        $publish = ($request->get('publish') == 'on') ? 1 : 2;
        $reserved = ($request->get('reserved') == 'on') ? 1 : 2;
        $request->merge([
            'album_name' => removeString($request->get('album_name'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
            'reserved' => $reserved,
        ]);

        return $request;
    }

    private function __validate($request, $id = null)
    {
        $rules = [
            'album_name' => 'required|string|max:191|unique:cms_video_album,album_name',
            'slug' => 'nullable|string|max:191|unique:cms_video_album,slug',
            'album_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'publish' => 'required|integer|min:1|max:2',
            'reserved' => 'required|integer|min:1|max:2',
        ];
        $messages = __('common::validation');
        $attributes = __('cmsadmin::models/video_albums.fields');

        if (!empty($id)) {
            $rules['album_name'] = $rules['album_name'] . ',' . $id . ',album_id,album_date,' . $request->album_date;
            $rules['slug'] = $rules['slug'] . ',' . $id . ',album_id';
        } else {
            $rules['album_name'] = $rules['album_name'] . ',NULL,album_id,album_date,' . $request->album_date;
        }

        $this->validate($request, $rules, $messages, $attributes);
    }
}
