<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\CmsAdmin\app\DataTables\VideoGalleryDataTable;
use Modules\CmsAdmin\app\Models\VideoAlbum;
use Modules\CmsAdmin\app\Models\VideoGallery;
use Modules\CmsAdmin\app\Repositories\VideoGalleryRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class VideoGalleryController extends BackendController
{
    public function __construct(VideoGalleryRepository $videoGalleryRepo)
    {
        $this->moduleName = 'cmsadmin.videoGalleries';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'togglePublish', 'toggleReserved', 'removeImage', 'imageEdit']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            buildPermissionMiddleware($this->moduleName, ['imageEdit', 'imageUpdate']) => ['imageEdit', 'imageUpdate'],

            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['toggleReserved']) => ['toggleReserved'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['removeImage']) => ['removeImage'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->langFile = 'cmsadmin::models/video_galleries';
        $this->commonLangFile = 'common::crud';
        $this->repository = $videoGalleryRepo;
        // $this->commonLangFile = 'common::crud';
        $id = request()->route('videoAlbum');
        $id = empty($id) ? 0 : $id;
        // define route for redirect
        $this->listRoute = route('cmsadmin.videoGalleries.index', ['videoAlbum' => $id]);
        // Define PATH
        $this->imageFilePath = storage_path(VIDEO_ALBUM_FILE_PATH);
        // Define DIMENSION
        $this->imageDimensions = json_decode(VIDEO_ALBUM_FILE_DIMENSIONS, true);
    }

    /**
     * Display a listing of the VideoGallery.
     */
    public function index($id, VideoGalleryDataTable $videoGalleryDataTable)
    {
        $videoAlbum = VideoAlbum::find($id);
        if (empty($videoAlbum)) {
            Flash::error(__('cmsadmin::models/video_albums.singular') . ' ' . __('common::messages.not_found'))->important();

            return redirect(route('cmsadmin.videoAlbums.index'));
        }

        return $videoGalleryDataTable->render('cmsadmin::video_galleries.index', ['videoAlbum' => $videoAlbum]);
    }

    /**
     * Show the form for creating a new VideoGallery.
     */
    public function create($id)
    {
        $videoAlbum = VideoAlbum::find($id);
        if (empty($videoAlbum)) {
            Flash::error(__('cmsadmin::models/video_albums.singular') . ' ' . __('common::messages.not_found'))->important();

            return redirect(route('cmsadmin.videoAlbums.index'));
        }

        return view('cmsadmin::video_galleries.create')
            ->with('videoAlbum', $videoAlbum)->with('id', null)
            ->with('publish', getOldData('publish'))
            ->with('reserved', getOldData('reserved'));
    }

    /**
     * Store a newly created VideoGallery in storage.
     */
    public function store($albumId, Request $request)
    {
        $videoAlbum = VideoAlbum::find($albumId);

        if (empty($videoAlbum)) {
            Flash::error(__('cmsadmin::models/video_albums.singular') . ' ' . __('common::messages.not_found'))->important();

            return redirect(route('cmsadmin.videoAlbums.index'));
        }
        $request = $this->__sanitize($request);
        $this->__validate($request, $albumId);

        $input = $request->all();

        $videoGallery = $videoAlbum->videoGalleries()->create($input);

        // manage images
        if ($request->has('feature_image')) {
            $file = $request->feature_image;
            if (!empty($file)) {
                $this->__manageImageFile($file, $videoGallery, 'feature_image');
            }
        }

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.videoGalleries.show', ['videoAlbum' => $albumId, 'gallery' => $videoGallery->video_id]));
    }

    /**
     * Display the specified VideoGallery.
     */
    public function show($albumId, $gallery)
    {
        $videoAlbum = VideoAlbum::find($albumId);
        if (empty($videoAlbum)) {
            Flash::error(__('cmsadmin::models/video_albums.singular') . ' ' . __('common::messages.not_found'))->important();

            return redirect(route('cmsadmin.videoAlbums.index'));
        }
        $videoGallery = $this->repository->find($gallery);

        if (!($videoGallery = $this->findModel($gallery))) {
            return redirect(route('cmsadmin.videoGalleries.index', $albumId));
        }

        return view('cmsadmin::video_galleries.show')->with('videoGallery', $videoGallery);
    }

    /**
     * Show the form for editing the specified VideoGallery.
     */
    public function edit($albumId, $gallery)
    {
        $videoAlbum = VideoAlbum::find($albumId);
        if (empty($videoAlbum)) {
            Flash::error(__('cmsadmin::models/video_albums.singular') . ' ' . __('common::messages.not_found'))->important();

            return redirect(route('cmsadmin.videoAlbums.index'));
        }

        if (!($videoGallery = $this->findModel($gallery))) {
            return redirect(route('cmsadmin.videoGalleries.index', $albumId));
        }

        return view('cmsadmin::video_galleries.edit')
            ->with('videoAlbum', $videoAlbum)
            ->with('videoGallery', $videoGallery)
            ->with('id', $videoGallery->video_id)
            ->with('publish', getOldData('publish', $videoGallery->publish))
            ->with('reserved', getOldData('reserved', $videoGallery->reserved));
    }

    /**
     * Update the specified VideoGallery in storage.
     */
    public function update($albumId, $gallery, Request $request)
    {
        $videoAlbum = VideoAlbum::find($albumId);
        if (empty($videoAlbum)) {
            Flash::error(__('cmsadmin::models/video_albums.singular') . ' ' . __('common::messages.not_found'))->important();

            return redirect(route('cmsadmin.videoAlbums.index'));
        }

        if (!($videoGallery = $this->findModel($gallery))) {
            return redirect(route('cmsadmin.videoGalleries.index', $albumId));
        }

        $request = $this->__sanitize($request);
        $this->__validate($request, $albumId, $gallery);

        $videoGallery = $this->repository->update($request->all(), $gallery);

        // manage images
        if ($request->has('feature_image')) {
            $file = $request->feature_image;
            $featureImagePre = $request->feature_image_pre;
            if (!empty($file) && $featureImagePre != $file) {
                $this->__manageImageFile($file, $videoGallery, 'feature_image');
                // delete old image
                $this->__deleteImageFile($featureImagePre);
            }
        }

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.videoGalleries.show', ['videoAlbum' => $albumId, 'gallery' => $gallery]));
    }

    // render image_update modal
    public function imageEdit($id, $field)
    {
        if (!$videoGallery = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('cmsadmin::video_galleries.image_edit')
            ->with('videoGallery', $videoGallery)
            ->with('field', $field)
            ->with('id', $videoGallery->video_id);
    }

    public function imageUpdate($id, $field, Request $request)
    {
        if (!$videoGallery = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $this->__validateImage($request, $field);
        $videoGallery = $this->repository->update($request->all(), $id);

        // manage images
        if ($request->has($field)) {
            $file = $request->$field;
            $fieldPreName = $field . '_pre';
            $fieldPre = $request->$fieldPreName;
            if (!empty($file) && $fieldPre != $file) {
                $this->__manageImageFile($file, $videoGallery, $field);
                // delete old image
                $this->__deleteImageFile($fieldPre);
            }
        }

        Flash::success(__('common::messages.updated_image', ['image' => __($this->langFile . '.fields.' . $field)]))->important();

        return redirect(route('cmsadmin.videoGalleries.index', ['videoAlbum' => $videoGallery->album_id]));
    }

    // validation
    private function __validateImage($request, $field)
    {
        $attributes = __($this->langFile . '.fields');
        $rules = [
            'feature_image' => 'nullable|string|max:100',
        ];
        $rules = [$field => $rules[$field]];
        $this->validate($request, $rules, [], $attributes);
    }

    // sanitize inputs
    private function __sanitize($request)
    {
        $publish = $request->get('publish') == 'on' ? 1 : 2;
        $reserved = $request->get('reserved') == 'on' ? 1 : 2;
        $request->merge([
            'caption' => removeString($request->get('caption'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
            'reserved' => $reserved,
        ]);

        return $request;
    }

    private function __validate($request, $albumId, $id = null)
    {
        $rules = [
            'caption' => 'required|string|max:255',
            'video_url' => 'required|url|string|max:191|unique:cms_video_gallery,video_url',
            'details' => 'nullable|string|max:65535',
            'feature_image' => 'nullable|string|max:100',
            'published_date' => 'required|date',
            'publish' => 'required|integer|min:1|max:2',
            'reserved' => 'required|integer|min:1|max:2',
        ];
        $messages = __('common::validation');
        $attribute1 = __($this->langFile . '.fields');
        $attributes2 = __($this->commonLangFile . '.fields');
        $attributes = $attribute1 + $attributes2;
        if (!empty($id)) {
            $rules['video_url'] = $rules['video_url'] . ',' . $id . ',video_id';
        } else {
            $rules['video_url'] = $rules['video_url'] . ',NULL,video_id';
        }
        $rules['video_url'] .= ',album_id,' . $albumId;
        $this->validate($request, $rules, $messages, $attributes);
    }
}
