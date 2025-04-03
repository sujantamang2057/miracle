<?php

namespace Modules\Common\app\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;
use File;
use Flash;
use Illuminate\Http\Request;
use Modules\Common\app\Components\FileStorageManager;
use Modules\Common\app\Components\ImageUploadManager;

class BackendController extends Controller
{
    public $moduleName;

    public $commonLangFile;

    protected $repository;

    protected $langFile;

    protected $listRoute;

    protected $trashListRoute;

    protected $detailRouteName;

    protected $imageFilePath;

    protected $imageDimensions;

    public function getConstructFrontEnd()
    {
        $frontendConstruct = new FrontendController;

        return $frontendConstruct->__construct();
    }

    public function reorder(Request $request)
    {
        $data = $request->json()->all();
        if (count($data)) {
            $displayOrders = array_column($data, 'position');
            rsort($displayOrders);
            $reorderedDisplayOrders = array_values($displayOrders);
            foreach ($data as $i => $key) {
                $pk = $key['index'];
                $showOrder = $reorderedDisplayOrders[$i] ?? $key['position'];
                if ($showOrder > 0) {
                    $model = $this->repository->find($pk);
                    $model->updateQuietly(['show_order' => $showOrder]);
                }
            }
        }

        return response()->noContent();
    }

    public function togglePublish(Request $request)
    {
        $selection = $request->id;
        if (!empty($selection)) {
            if (is_array($selection)) {
                foreach ($selection as $id) {
                    $this->__setPublish($id);
                }

                return response()->json([
                    'msgType' => 'success',
                    'msg' => __('common::messages.publish_toggle', ['model' => __($this->langFile . '.plural')]),
                ]);
            } else {
                if ($this->__setPublish($selection)) {
                    return response()->json([
                        'msgType' => 'success',
                        'msg' => __('common::messages.publish_toggle', ['model' => __($this->langFile . '.singular')]),
                    ]);
                }
            }
        }

        return response()->json(['message' => __('common::messages.toggle_publish_failed')]);
    }

    public function toggleReserved(Request $request)
    {
        $selection = $request->id;
        if (!empty($selection)) {
            if (is_array($selection)) {
                foreach ($selection as $id) {
                    $this->__setReserved($id);
                }

                return response()->noContent();
            } else {
                if ($this->__setReserved($selection)) {
                    return response()->json([
                        'msgType' => 'success',
                        'msg' => __('common::messages.reserved_toggle', ['model' => __($this->langFile . '.singular')]),
                    ]);
                }
            }
        }

        return response()->json(['message' => __('common::messages.toggle_reserved_failed')]);
    }

    public function destroy(Request $request)
    {
        $selection = $request->id;
        if (!empty($selection)) {
            if (!$this->__isReserved($selection)) {
                if (is_array($selection)) {
                    foreach ($selection as $id) {
                        $this->__delete($id);
                    }

                    return response()->json([
                        'msg' => __('common::messages.deleted', ['model' => __($this->langFile . '.plural')]),
                        'msgType' => 'success',
                    ]);
                } else {
                    if ($this->__delete($selection)) {
                        Flash::success(__('common::messages.deleted', ['model' => __($this->langFile . '.singular')]))->important();

                        return redirect($this->listRoute);
                    }
                }
            } else {
                return response()->json([
                    'msg' => __('common::messages.delete_error', ['model' => __($this->langFile . '.singular')]),
                    'msgType' => 'danger',
                ]);
            }
        }

        Flash::error(__('common::messages.delete_error', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect($this->listRoute);
    }

    public function permanentDestroy(Request $request)
    {
        $selection = $request->id;

        if (!empty($selection)) {
            $deleteData = false;

            if (is_array($selection)) {
                foreach ($selection as $id) {
                    $deleteData = $this->__forceDelete($id);

                    if ($deleteData === false) {
                        return response()->json([
                            'msg' => __('common::messages.being_used', ['model' => __($this->langFile . '.plural')]),
                            'msgType' => 'danger',
                        ]);
                    }
                }

                return response()->json([
                    'msg' => __('common::messages.permanently_deleted', ['model' => __($this->langFile . '.singular')]),
                    'msgType' => 'success',
                ]);

            } else {
                if ($this->__forceDelete($selection) === false) {
                    Flash::error(__('common::messages.being_used', ['model' => __($this->langFile . '.plural')]))->important();
                } else {
                    Flash::success(__('common::messages.permanently_deleted', ['model' => __($this->langFile . '.singular')]))->important();
                }

                return redirect($this->trashListRoute);
            }

        }

        Flash::error(__('common::messages.delete_error', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect($this->trashListRoute);
    }

    public function restore(Request $request)
    {
        $responseData = [
            'msg' => __('common::crud.messages.trash_restore_failed'),
        ];

        $selection = $request->id;
        if (!empty($selection)) {
            if (is_array($selection)) {
                foreach ($selection as $id) {
                    $this->__restore($id);
                }

                $responseData['msg'] = __('common::crud.messages.trash_restore_success');
                $responseData['msgType'] = 'success';
            } else {
                if ($this->__restore($selection)) {
                    $responseData['msg'] = __('common::crud.messages.trash_restore_success');
                    $responseData['url'] = route($this->detailRouteName, $selection);
                }
            }
        }

        return response()->json($responseData);
    }

    public function findModel($id)
    {
        $model = (is_numeric($id)) ? $this->repository->find($id) : null;

        if (empty($model)) {
            Flash::error(__($this->langFile . '.singular') . ' ' . __('common::messages.not_found'))->important();
        }

        return $model;
    }

    /**
     * Remove optional image in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function removeImage($id, Request $request)
    {
        $dbData = $this->repository->find($id);
        $success = false;
        $msg = '';
        if (empty($dbData)) {
            $msg = 'Record not found!';
        } else {
            $msg = 'Incorrect parameters!';
            if ($request->has('image_field_name')) {
                $input = $request->all();
                $fieldName = isset($input['image_field_name']) ? $input['image_field_name'] : null;
                if ($fieldName) {
                    $updateData = [$fieldName => null];
                    $dbDataTmp = $this->repository->update($updateData, $id);
                    // delete image file as well
                    $this->__deleteImageFile($dbData->$fieldName);
                    //
                    $success = true;
                    $msg = __('common::messages.image_removed_successfully');
                }
            }
        }

        $return = [
            'success' => $success,
            'msg' => $msg,
        ];

        return response()->json($return);
    }

    private function __isReserved($ids)
    {
        $model = $this->repository->makeModel();

        if ($model->hasAttribute('reserved')) {
            $query = DB::table($model->getTable())->select($model->getKeyName());
            if (is_array($ids)) {
                $query->whereIn($model->getKeyName(), $ids);
            } else {
                $query->where($model->getKeyName(), $ids);
            }
            $query->where('reserved', 1)->get();

            return $query->count();
        } else {
            return 0;
        }
    }

    private function __setPublish($id)
    {
        $model = $this->repository->find($id);
        if (!empty($model)) {
            $publish = ($model->publish == 2) ? 1 : 2;
            $model->update([
                'publish' => $publish,
            ]);

            return true;
        }

        return false;
    }

    private function __setReserved($id)
    {
        $model = $this->repository->find($id);
        if (!empty($model)) {
            $reserved = ($model->reserved == 2) ? 1 : 2;
            $model->update([
                'reserved' => $reserved,
            ]);

            return true;
        }

        return false;
    }

    private function __delete($id)
    {
        $model = $this->repository->find($id);

        if (!empty($model)) {
            $this->repository->delete($id);
            $tableName = $model->getTable();
            $statusField = in_array($tableName, ACTIVE_FIELD_TABLES) ? 'active' : 'publish';
            $model->update([
                'deleted_by' => auth()->user() ? auth()->user()->id : 1,
                $statusField => 2,
            ]);
            if ($tableName == 'cms_gallery') {
                $album = $model->album()->first();
                $imageCount = $album->galleries()->count();
                $album->update(['image_count' => $imageCount]);
            }

            return true;
        }

        return false;
    }

    private function __forceDelete($id)
    {

        $model = $this->repository->findTrashed($id);

        if (!$model) {
            return false;
        }
        if (method_exists($model, 'getCategory') && $model->getCategory()->count() > 0) {

            return false;
        }
        $model->forceDelete();

        return true;
    }

    private function __restore($id)
    {
        $model = $this->repository->findTrashed($id);

        if (!empty($model)) {
            $model->restore();

            return true;
        }

        return false;
    }

    // manage image file
    protected function __manageImageFile($file = null, $model = null, $attribute = null)
    {
        if (!empty($file) && !empty($model)) {
            $imageFileName = ImageUploadManager::processUploadedImage($file, $this->imageFilePath, $this->imageDimensions);
            if (!empty($imageFileName)) {
                $model->timestamps = false;
                $model->updateQuietly([$attribute => $imageFileName]);
            }
        }
    }

    // delete image file
    protected function __deleteImageFile($imageFile = null)
    {
        if (!empty($imageFile)) {
            FileStorageManager::deleteImageFile($imageFile, $this->imageFilePath, $this->imageDimensions);
        }
    }

    protected function __loadCloneData($model, $imageField)
    {
        if (empty($model)) {
            return $model;
        }
        $mode = request()->mode;
        $modelKeyName = $model->primaryKey;
        if ($mode == 'image') {
            if (!empty($model->$imageField) && File::exists($this->imageFilePath . DS . $model->$imageField)) {
                $imagePre = $imageField . '_pre';
                $model->$imagePre = $model->$imageField;
                File::copy($this->imageFilePath . DS . $model->$imageField, storage_path(UPLOAD_FILE_DIR_NAME_TMP . DS . $model->$imageField));
            }
        }

        $model->$imageField = $model->$modelKeyName = $model->slug = null;

        return $model;
    }
}
