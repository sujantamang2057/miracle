<?php

namespace Modules\Common\app\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;

class ImageHandlerController extends AppBaseController
{
    public function fileupload(Request $request)
    {
        $moduleName = $request->input('module_name');
        $fileElementName = $request->input('file_element_name');
        $fileElementName = (!empty($fileElementName)) ? $fileElementName : 'filepond';
        $multidata = $request->file('multidata');
        if ($multidata) {
            $index = $request->input('index');
            $image = isset($multidata[$index][$fileElementName]) ? $multidata[$index][$fileElementName] : null;
        } else {
            $image = $request->file($fileElementName);
        }
        $filename = null;
        if ($image) {
            if (is_array($image)) {
                $image = isset($image[0]) ? $image[0] : $image;
            }
            $filename = $image->getClientOriginalName();
            $image->move(storage_path(UPLOAD_FILE_DIR_NAME_TMP), $filename);

            return response($filename, 200)
                ->header('Content-Type', 'text/plain');
        }

        return response('', 500)
            ->header('Content-Type', 'text/plain');
    }

    public function destroy(Request $request)
    {
        $filename = $request->getContent();
        $path = storage_path(UPLOAD_FILE_DIR_NAME_TMP) . DS . $filename;
        if ($filename && file_exists($path)) {
            unlink($path);
        }

        // return response()->noContent();
        return response('Ok', 200)
            ->header('Content-Type', 'text/plain');
    }
}
