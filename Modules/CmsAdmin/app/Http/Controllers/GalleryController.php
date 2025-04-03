<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\CmsAdmin\app\DataTables\GalleryDataTable;
use Modules\CmsAdmin\app\Models\Album;
use Modules\CmsAdmin\app\Models\Gallery;
use Modules\CmsAdmin\app\Repositories\GalleryRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class GalleryController extends BackendController
{
    public function __construct(GalleryRepository $galleryRepo)
    {
        $this->moduleName = 'cmsadmin.galleries';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'editable', 'destroy', 'togglePublish', 'imageEdit']) => ['index'],

            // Permission Check - Exact
            buildPermissionMiddleware($this->moduleName, ['imageEdit', 'imageUpdate']) => ['imageEdit', 'imageUpdate'],
            buildCanMiddleware($this->moduleName, ['editable']) => ['editable'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->langFile = 'cmsadmin::models/galleries';
        $this->repository = $galleryRepo;
        $id = request()->route('album');
        $id = empty($id) ? 0 : $id;
        // define route for redirect
        $this->listRoute = route('cmsadmin.galleries.index', ['album' => $id]);
        // image PATH
        $this->imageFilePath = storage_path(ALBUM_FILE_PATH);
        // image DIMENSION
        $this->imageDimensions = json_decode(ALBUM_FILE_DIMENSIONS, true);
    }

    /**
     * Display a listing of the Gallery.
     */
    public function index($id, GalleryDataTable $galleryDataTable)
    {
        $album = Album::find($id);

        if (empty($album)) {
            Flash::error(__('cmsadmin::models/albums.singular') . ' ' . __('common::messages.not_found'))->important();

            return redirect(route('cmsadmin.albums.index'));
        }

        return $galleryDataTable->render('cmsadmin::galleries.index', ['album' => $album]);
    }

    public function editable(Request $request)
    {
        $id = $request->route('gallery');
        $gallery = $this->findModel($id);

        if (empty($gallery)) {
            return response()->json([
                'msg' => __($this->langFile . '.singular') . ' ' . __('common::messages.not_found'),
                'msgType' => 'danger',
            ]);
        }

        $field = $request->input('field');
        $value = $request->input('value');

        if (empty($field) || empty($value)) {
            return response()->json([
                'msg' => __('common::messages.update_error', ['model' => __($this->langFile . '.singular')]),
                'msgType' => 'danger',
            ]);
        }

        $gallery->update([$field => $value]);

        return response()->json([
            'msg' => __('common::messages.updated', ['model' => __($this->langFile . '.singular')]),
            'msgType' => 'success',
        ]);
    }

    public function removeGalleryImage($album, Request $request)
    {
        $id = $request->route('gallery');
        $gallery = $this->findModel($id);
        $album = Album::find($album);
        if (!$gallery) {
            return response()->json([
                'success' => false,
                'msg' => __($this->langFile . '.singular') . ' ' . __('common::messages.not_found'),
            ]);
        }

        // Delete the gallery record or related image
        $this->repository->delete($gallery->image_id);
        $gallery->update([
            'deleted_by' => auth()->user() ? auth()->user()->id : 1,

        ]);
        $album->update([
            'image_count' => $album->galleries()->count(),
        ]);

        return response()->json([
            'success' => true,
            'msg' => __('common::messages.deleted', ['model' => __($this->langFile . '.plural')]),
        ]);
    }

    private function __validateImage($request, $field)
    {
        $attributes = __($this->langFile . '.fields');
        $rules = Gallery::$rules;
        $rules = [$field => $rules[$field]];
        $this->validate($request, $rules, [], $attributes);
    }

    public function imageEdit($id, $field)
    {
        if (!$gallery = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('cmsadmin::galleries.image_edit')
            ->with('gallery', $gallery)
            ->with('field', $field)
            ->with('id', $gallery->album_id);
    }

    public function imageUpdate($id, $field, Request $request)
    {
        if (!$galleryImage = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $this->__validateImage($request, $field);
        $galleryImage = $this->repository->update($request->all(), $id);

        if ($request->has($field)) {
            $file = $request->$field;
            $fieldPreName = $field . '_pre';
            $fieldPre = $request->$fieldPreName;
            if (!empty($file) && $fieldPre != $file) {
                $this->__manageImageFile($file, $galleryImage, $field);
                $this->__deleteImageFile($fieldPre);
            }
        }

        Flash::success(__('common::messages.updated_image', ['image' => __($this->langFile . '.fields.' . $field)]))->important();

        return redirect(route('cmsadmin.galleries.index', ['album' => $galleryImage->album_id]));

    }
}
