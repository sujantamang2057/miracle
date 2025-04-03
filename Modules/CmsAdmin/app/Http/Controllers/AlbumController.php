<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\CmsAdmin\app\DataTables\AlbumDataTable;
use Modules\CmsAdmin\app\DataTables\AlbumTrashDataTable;
use Modules\CmsAdmin\app\Models\Album;
use Modules\CmsAdmin\app\Repositories\AlbumRepository;
use Modules\Common\app\Components\ImageUploadManager;
use Modules\Common\app\Http\Controllers\BackendController;

class AlbumController extends BackendController
{
    public function __construct(AlbumRepository $albumRepo)
    {
        $this->moduleName = 'cmsadmin.albums';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'togglePublish', 'toggleReserved', 'setCoverImage', 'trashList']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['toggleReserved']) => ['toggleReserved'],
            buildCanMiddleware($this->moduleName, ['setCoverImage']) => ['setCoverImage'],
            buildCanMiddleware($this->moduleName, ['trashList']) => ['trashList'],
            buildCanMiddleware($this->moduleName, ['restore']) => ['restore'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->langFile = 'cmsadmin::models/albums';
        $this->repository = $albumRepo;
        // define route for redirect
        $this->listRoute = route('cmsadmin.albums.index');
        $this->trashListRoute = route('cmsadmin.albums.trashList');
        $this->detailRouteName = 'cmsadmin.albums.show';
        // image PATH
        $this->imageFilePath = storage_path(ALBUM_FILE_PATH);
        // image DIMENSION
        $this->imageDimensions = json_decode(ALBUM_FILE_DIMENSIONS, true);
    }

    /**
     * Display a listing of the Album.
     */
    public function index(AlbumDataTable $albumDataTable)
    {
        return $albumDataTable->render('cmsadmin::albums.index');
    }

    /**
     * Display a listing of the Trashed Album.
     */
    public function trashList(AlbumTrashDataTable $albumTrashDataTable)
    {
        return $albumTrashDataTable->render('cmsadmin::albums.trash');
    }

    /**
     * Show the form for creating a new Album.
     */
    public function create()
    {
        return view('cmsadmin::albums.create')
            ->with('id', null)
            ->with('publish', getOldData('publish'))
            ->with('reserved', getOldData('reserved'));
    }

    /**
     * Store a newly created Album in storage.
     */
    public function store(Request $request)
    {
        // sanitize and validate input
        $request = $this->__sanitize($request);
        $this->__validate($request);

        $input = $request->all();

        $images = $request->input('image_name');
        unset($input['image_name']);

        $album = $this->repository->create($input);
        $this->__manageImageFiles($images, $album);

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.albums.show', ['album' => $album->album_id]));
    }

    /**
     * Display the specified Album.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);
        if ($mode == 'trash-restore') {
            if (!$album = Album::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($album->trashed()) {
                return view('cmsadmin::albums.show-trash')
                    ->with('album', $album)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('cmsadmin.albums.show', ['album' => $album->album_id]));
            }
        } else {
            if (!$album = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('cmsadmin::albums.show')
            ->with('album', $album)
            ->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified Album.
     */
    public function edit($id)
    {
        if (!$album = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('cmsadmin::albums.edit')
            ->with('id', $album->album_id)
            ->with('publish', getOldData('publish', $album->publish))
            ->with('reserved', getOldData('reserved', $album->reserved))
            ->with('album', $album);
    }

    /**
     * Update the specified Album in storage.
     */
    public function update($id, Request $request)
    {
        if (!$album = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $request = $this->__sanitize($request);
        $this->__validate($request, $id);
        $input = $request->all();
        $images = $request->input('image_name');
        unset($input['image_name']);

        $album = $this->repository->update($input, $id);
        $this->__manageImageFiles($images, $album);

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.albums.show', $id));
    }

    public function setCoverImage($id, Request $request)
    {
        if ($request->ajax()) {
            $album = $this->repository->find($id);

            if (empty($album)) {
                Flash::error(__($this->langFile . '.singular') . ' ' . __('common::messages.not_found'))->important();

                return response()->noContent();
            }

            $galleryId = $request->input('gallery');
            $response = [
                'msg' => __($this->langFile . '.messages.failed_to_set_cover_image'),
                'msgType' => 'danger',
            ];

            if ($galleryId) {
                $album->update(['cover_image_id' => $galleryId]);
                $response['msg'] = __($this->langFile . '.messages.cover_image_set_successfully');
                $response['msgType'] = 'success';
            }

            return response()->json($response);
        }

        return redirect(route('cmsadmin.albums.index'));
    }

    // sanitize inputs
    private function __sanitize($request)
    {
        $publish = ($request->get('publish') == 'on') ? 1 : 2;
        $filteredImages = array_values(array_filter($request->input('image_name', [])));
        $reserved = ($request->get('reserved') == 'on') ? 1 : 2;
        $request->merge([
            'title' => removeString($request->get('title'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'slug' => removeString($request->get('slug'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
            'reserved' => $reserved,
            'image_name' => $filteredImages,
        ]);

        return $request;
    }

    // validate function
    private function __validate($request, $id = null)
    {
        $rules = [
            'date' => 'required|date',
            'title' => 'required|string|max:255|unique:cms_album,title',
            'slug' => 'nullable|string|max:255|unique:cms_album,slug',
            'detail' => 'nullable|string|max:65535',
            'publish' => 'required|integer|min:1|max:2',
            'reserved' => 'required|integer|min:1|max:2',
        ];
        $messages = __('common::validation');

        $attributes = __($this->langFile . '.fields');
        if (!empty($id)) {
            $rules['title'] = $rules['title'] . ',' . $id . ',album_id';
            $rules['slug'] = $rules['slug'] . ',' . $id . ',album_id';
        }

        $this->validate($request, $rules, $messages, $attributes);
    }

    // manage image file
    private function __manageImageFiles($files, $album)
    {
        if (!empty($files) && !empty($album)) {
            $data = [
                'album_id' => $album->album_id,
                'caption' => null,
                'publish' => 1,
                'image_name' => null,
            ];
            $imageCount = 0;
            foreach ($files as $key => $file) {
                if (!empty($file)) {
                    $imageFileName = ImageUploadManager::processUploadedImage($file, $this->imageFilePath, $this->imageDimensions);
                    $data['caption'] = $file;
                    $data['image_name'] = $imageFileName;
                    $album->galleries()->create($data);
                    $imageCount++;
                }
            }
            $album->timestamps = false;
            $album->updateQuietly(['image_count' => $album->image_count + $imageCount]);
        }
    }
}
