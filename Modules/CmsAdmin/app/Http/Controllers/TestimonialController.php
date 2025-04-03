<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\CmsAdmin\app\DataTables\TestimonialDataTable;
use Modules\CmsAdmin\app\DataTables\TestimonialTrashDataTable;
use Modules\CmsAdmin\app\Models\Testimonial;
use Modules\CmsAdmin\app\Repositories\TestimonialRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class TestimonialController extends BackendController
{
    public function __construct(TestimonialRepository $testimonialRepo)
    {
        $this->moduleName = 'cmsadmin.testimonials';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'togglePublish', 'toggleReserved', 'trashList', 'imageEdit']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            buildPermissionMiddleware($this->moduleName, ['imageEdit', 'imageUpdate']) => ['imageEdit', 'imageUpdate'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['toggleReserved']) => ['toggleReserved'],
            buildCanMiddleware($this->moduleName, ['trashList']) => ['trashList'],
            buildCanMiddleware($this->moduleName, ['restore']) => ['restore'],
            buildCanMiddleware($this->moduleName, ['removeImage']) => ['removeImage'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->repository = $testimonialRepo;
        $this->langFile = 'cmsadmin::models/testimonials';
        // define route for redirect
        $this->listRoute = route('cmsadmin.testimonials.index');
        $this->trashListRoute = route('cmsadmin.testimonials.trashList');
        $this->detailRouteName = 'cmsadmin.testimonials.show';
        // image path
        $this->imageFilePath = storage_path(TESTIMONIAL_FILE_PATH);
        // image Dimension
        $this->imageDimensions = json_decode(TESTIMONIAL_FILE_DIMENSIONS, true);
    }

    /**
     * Display a listing of the Testimonial.
     */
    public function index(TestimonialDataTable $testimonialDataTable)
    {
        return $testimonialDataTable->render('cmsadmin::testimonials.index');
    }

    /**
     * Display a listing of the Trashed Testimonial.
     */
    public function trashList(TestimonialTrashDataTable $testimonialTrashDataTable)
    {
        return $testimonialTrashDataTable->render('cmsadmin::testimonials.trash');
    }

    /**
     * Show the form for creating a new Testimonial.
     */
    public function create()
    {
        return view('cmsadmin::testimonials.create')
            ->with('id', null)
            ->with('publish', getOldData('publish'))
            ->with('reserved', getOldData('reserved'));
    }

    /**
     * Store a newly created Testimonial in storage.
     */
    public function store(Request $request)
    {
        // sanitize inputs
        $request = $this->__sanitize($request);
        $this->__validate($request);
        $input = $request->all();

        $testimonial = $this->repository->create($input);

        // manage image file
        if ($request->has('tm_profile_image')) {
            $file = $request->tm_profile_image;
            if (!empty($file)) {
                $this->__manageImageFile($file, $testimonial, 'tm_profile_image');
            }
        }

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.testimonials.show', ['testimonial' => $testimonial->testimonial_id]));
    }

    /**
     * Display the specified Testimonial.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);
        if ($mode == 'trash-restore') {
            if (!$testimonial = Testimonial::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($testimonial->trashed()) {
                return view('cmsadmin::testimonials.show-trash')
                    ->with('testimonial', $testimonial)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('cmsadmin.testimonials.show', ['testimonial' => $testimonial->testimonial_id]));
            }
        } else {
            if (!$testimonial = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('cmsadmin::testimonials.show')
            ->with('testimonial', $testimonial)
            ->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified Testimonial.
     */
    public function edit($id)
    {
        if (!$testimonial = $this->findModel($id)) {
            return redirect($this->listRoute);
        }
        $testimonial->tm_profile_image_pre = $testimonial->tm_profile_image;

        return view('cmsadmin::testimonials.edit')
            ->with('testimonial', $testimonial)
            ->with('id', $testimonial->testimonial_id)
            ->with('publish', getOldData('publish', $testimonial->publish))
            ->with('reserved', getOldData('reserved', $testimonial->reserved));
    }

    /**
     * Update the specified Testimonial in storage.
     */
    public function update($id, Request $request)
    {
        if (!$testimonial = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        // sanitize inputs
        $request = $this->__sanitize($request);
        $this->__validate($request, $testimonial->testimonial_id);
        $input = $request->all();

        $testimonial = $this->repository->update($input, $id);

        // update image
        if ($request->has('tm_profile_image')) {
            $file = $request->tm_profile_image;
            $testiImagePre = $request->tm_profile_image_pre;
            if (!empty($file) && $file != $testiImagePre) {
                $this->__manageImageFile($file, $testimonial, 'tm_profile_image');
                $this->__deleteImageFile($testiImagePre);
            }
        }

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.testimonials.show', $id));
    }

    // render model
    public function imageEdit($id, $field)
    {
        if (!$testimonial = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('cmsadmin::testimonials.image_edit')
            ->with('testimonial', $testimonial)
            ->with('field', $field)
            ->with('id', $testimonial->testimonial_id);
    }

    public function imageUpdate($id, $field, Request $request)
    {
        if (!$testimonial = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $this->__validateImage($request, $field);
        $testimonial = $this->repository->update($request->all(), $id);

        // manage images
        if ($request->has($field)) {
            $file = $request->$field;
            $fieldPreName = $field . '_pre';
            $fieldPre = $request->$fieldPreName;
            if (!empty($file) && $fieldPre != $file) {
                $this->__manageImageFile($file, $testimonial, $field);
                // delete old image
                $this->__deleteImageFile($fieldPre);
            }
        }

        Flash::success(__('common::messages.updated_image', ['image' => __($this->langFile . '.fields.' . $field)]))->important();

        return redirect(route('cmsadmin.testimonials.index'));
    }

    // validation
    private function __validateImage($request, $field)
    {
        $attributes = __($this->langFile . '.fields');
        $rules = [
            'tm_profile_image' => 'nullable|string|max:100',
        ];
        $rules = [$field => $rules[$field]];
        $this->validate($request, $rules, [], $attributes);
    }

    // sanitize  input
    private function __sanitize($request)
    {
        $publish = ($request->get('publish') == 'on') ? 1 : 2;
        $reserved = ($request->get('reserved') == 'on') ? 1 : 2;
        $request->merge([
            'tm_name' => removeString($request->get('tm_name'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
            'reserved' => $reserved,
        ]);

        return $request;
    }

    private function __validate($request, $id = null)
    {
        $rules = [
            'tm_name' => 'required|string|max:255',
            'tm_email' => 'required|string|email:rfc,dns|max:100|unique:cms_testimonial,tm_email',
            'tm_profile_image' => 'nullable|string|max:100',
            'tm_company' => 'nullable|string|max:255',
            'tm_designation' => 'nullable|string|max:100',
            'tm_testimonial' => 'required|string|max:500',
            'sns_fb' => 'nullable|string|max:255|url',
            'sns_linkedin' => 'nullable|string|max:255|url',
            'sns_twitter' => 'nullable|string|max:255|url',
            'sns_instagram' => 'nullable|string|max:255|url',
            'sns_youtube' => 'nullable|string|max:255|url',
            'publish' => 'required|integer|min:1|max:2',
            'reserved' => 'required|integer|min:1|max:2',
        ];
        $messages = __('common::validation');
        $attributes = __($this->langFile . '.fields');
        if (!empty($id)) {
            $rules['tm_email'] = $rules['tm_email'] . ',' . $id . ',testimonial_id';
        }

        $this->validate($request, $rules, $messages, $attributes);
    }
}
