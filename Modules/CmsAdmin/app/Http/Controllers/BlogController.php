<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\Cms\app\Components\Helpers\BlogHelper;
use Modules\CmsAdmin\app\DataTables\BlogDataTable;
use Modules\CmsAdmin\app\DataTables\BlogTrashDataTable;
use Modules\CmsAdmin\app\Models\Blog;
use Modules\CmsAdmin\app\Models\BlogCategory;
use Modules\CmsAdmin\app\Models\BlogDetail;
use Modules\CmsAdmin\app\Repositories\BlogRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class BlogController extends BackendController
{
    public function __construct(BlogRepository $blogRepo)
    {
        parent::getConstructFrontEnd();
        $this->moduleName = 'cmsadmin.blogs';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'multidata', 'restore', 'saveMultidata', 'togglePublish', 'toggleReserved', 'trashList', 'imageEdit']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            buildPermissionMiddleware($this->moduleName, ['imageEdit', 'imageUpdate']) => ['imageEdit', 'imageUpdate'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['toggleReserved']) => ['toggleReserved'],
            buildCanMiddleware($this->moduleName, ['restore']) => ['restore'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['multidata']) => ['multidata'],
            buildCanMiddleware($this->moduleName, ['saveMultidata']) => ['saveMultidata'],
            buildCanMiddleware($this->moduleName, ['trashList']) => ['trashList'],
            buildCanMiddleware($this->moduleName, ['removeImage']) => ['removeImage'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->repository = $blogRepo;
        $this->langFile = 'cmsadmin::models/blogs';
        $this->listRoute = route('cmsadmin.blogs.index');
        $this->trashListRoute = route('cmsadmin.blogs.trashList');
        $this->detailRouteName = 'cmsadmin.blogs.show';
        $this->imageFilePath = storage_path(BLOG_FILE_PATH);
        $this->imageDimensions = json_decode(BLOG_FILE_DIMENSIONS, true);
    }

    /**
     * Display a listing of the Blog.
     */
    public function index(BlogDataTable $blogDataTable)
    {
        return $blogDataTable->render('cmsadmin::blogs.index');
    }

    /**
     * Display a listing of the Trashed Blog.
     */
    public function trashList(BlogTrashDataTable $blogTrashDataTable)
    {
        return $blogTrashDataTable->render('cmsadmin::blogs.trash');
    }

    /**
     * Show the form for creating a new Blog.
     */
    public function create()
    {
        $blogCatList = BlogCategory::getBlogCatList(null, true);

        return view('cmsadmin::blogs.create')->with('blogCatList', $blogCatList)->with('id', null)->with('publish', getOldData('publish'));
    }

    /**
     * Store a newly created Blog in storage.
     */
    public function store(Request $request)
    {
        // sanitize request
        $request = $this->__sanitize($request);
        $this->__validate($request);
        $input = $request->all();

        $blog = $this->repository->create($input);

        // manage images
        if ($request->has('thumb_image')) {
            $file = $request->thumb_image;
            if (!empty($file)) {
                $this->__manageImageFile($file, $blog, 'thumb_image');
            }
        }
        if ($request->has('image')) {
            $file = $request->image;
            if (!empty($file)) {
                $this->__manageImageFile($file, $blog, 'image');
            }
        }

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.blogs.show', ['blog' => $blog->blog_id]));
    }

    /**
     * Display the specified Blog.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);
        if ($mode == 'trash-restore') {
            if (!$blog = Blog::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($blog->trashed()) {
                return view('cmsadmin::blogs.show-trash')
                    ->with('blog', $blog)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('cmsadmin.blogs.show', ['blog' => $blog->blog_id]));
            }
        } else {
            if (!$blog = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('cmsadmin::blogs.show')
            ->with('blog', $blog)
            ->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified Blog.
     */
    public function edit($id)
    {
        if (!($blog = $this->findModel($id))) {
            return redirect($this->listRoute);
        }
        $blogCatList = BlogCategory::getBlogCatList($blog->cat_id, true);
        // view saved image
        $blog->thumb_image_pre = $blog->thumb_image;
        $blog->image_pre = $blog->image;

        return view('cmsadmin::blogs.edit')
            ->with('blog', $blog)
            ->with('blogCatList', $blogCatList)
            ->with('id', $blog->blog_id)
            ->with('publish', getOldData('publish', $blog->publish));
    }

    /**
     * Update the specified Blog in storage.
     */
    public function update($id, Request $request)
    {
        if (!($blog = $this->findModel($id))) {
            return redirect($this->listRoute);
        }

        // sanitize request
        $request = $this->__sanitize($request);
        $this->__validate($request, $blog->blog_id);
        $input = $request->all();

        $blog = $this->repository->update($input, $id);

        // manage images
        if ($request->has('thumb_image')) {
            $file = $request->thumb_image;
            $blogImagePre = $request->thumb_image_pre;
            if (!empty($file) && $blogImagePre != $file) {
                $this->__manageImageFile($file, $blog, 'thumb_image');
                // delete old image
                $this->__deleteImageFile($blogImagePre);
            }
        }

        if ($request->has('image')) {
            $file = $request->image;
            $blogImagePre = $request->image_pre;
            if (!empty($file) && $blogImagePre != $file) {
                $this->__manageImageFile($file, $blog, 'image');
                // delete old image
                $this->__deleteImageFile($blogImagePre);
            }
        }

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.blogs.show', $id));
    }

    // handle multi data for blog

    public function multidata($id)
    {
        if (!($blog = $this->findModel($id))) {
            return redirect($this->listRoute);
        }

        $blogDetails = $blog->details;
        $multidata = [];
        if (!empty($blogDetails)) {
            foreach ($blogDetails as $key => $detail) {
                $multidata[$key]['detail_id'] = $detail->detail_id;
                $multidata[$key]['title'] = $detail->title;
                $multidata[$key]['layout'] = $detail->layout;
                $multidata[$key]['image_pre'] = $multidata[$key]['image'] = $detail->image;
                $multidata[$key]['detail'] = $detail->detail;
            }
        }
        $oldMultidata = old('multidata');
        if (!empty($oldMultidata)) {
            foreach ($oldMultidata as $key => $old) {
                $multidata[$key]['title'] = isset($old['title']) ? $old['title'] : null;
                $multidata[$key]['layout'] = isset($old['layout']) ? $old['layout'] : 1;
                $multidata[$key]['image'] = isset($old['image']) ? $old['image'] : null;
                $multidata[$key]['detail'] = isset($old['detail']) ? $old['detail'] : null;
            }
        }

        return view('cmsadmin::blogs.multidata')->with('blog', $blog)->with('multidata', $multidata);
    }

    public function saveMultiData($id, Request $request)
    {
        if (!($blog = $this->findModel($id))) {
            return redirect($this->listRoute);
        }

        $blogDetails = $blog->details;
        $rules = [
            'multidata.*.title' => 'required|string|max:255',
            'multidata.*.image' => 'nullable|string|max:255',
            'multidata.*.detail' => 'nullable|string|max:65535',
            'multidata.*.layout' => 'nullable|integer|min:1|max:4',
        ];
        $this->validate($request, $rules, ['required' => __('common::messages.is_required', ['field' => __('common::multidata.fields.title')])]);
        $multidata = $request->input('multidata');
        $oldItems = !empty($blogDetails) ? $blogDetails->pluck('detail_id')->toArray() : [];
        $safeItems = [];

        if (!empty($multidata)) {
            foreach ($multidata as $key => $data) {
                $detailId = isset($data['detail_id']) ? $data['detail_id'] : null;
                if (!empty($detailId) && in_array($detailId, $oldItems)) {
                    $safeItems[] = $data['detail_id'];
                }
                $image = isset($data['image']) ? $data['image'] : null;
                $imagePre = isset($data['image_pre']) ? $data['image_pre'] : null;
                unset($data['image_pre']);
                // save or update blog detail
                $currentBlogDetail = $blog->details()->updateOrCreate(['detail_id' => $detailId], $data);

                if (!empty($image) && $imagePre != $image) {
                    $this->__manageImageFile($image, $currentBlogDetail, 'image');
                    // delete old image
                    $this->__deleteImageFile($imagePre);
                }
            }
        }
        $unsafeItems = array_diff($oldItems, $safeItems);
        BlogDetail::find($unsafeItems)->each->delete();

        return redirect(route('cmsadmin.blogDetails.index', $id));
    }

    // render model
    public function imageEdit($id, $field)
    {
        if (!$blog = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('cmsadmin::blogs.image_edit')
            ->with('blog', $blog)
            ->with('field', $field)
            ->with('id', $blog->blog_id);
    }

    public function imageUpdate($id, $field, Request $request)
    {
        if (!$blog = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $this->__validateImage($request, $field);
        $blog = $this->repository->update($request->all(), $id);

        // manage images
        if ($request->has($field)) {
            $file = $request->$field;
            $fieldPreName = $field . '_pre';
            $fieldPre = $request->$fieldPreName;
            if (!empty($file) && $fieldPre != $file) {
                $this->__manageImageFile($file, $blog, $field);
                // delete old image
                $this->__deleteImageFile($fieldPre);
            }
        }

        Flash::success(__('common::messages.updated_image', ['image' => __($this->langFile . '.fields.' . $field)]))->important();

        return redirect(route('cmsadmin.blogs.index'));
    }

    public function preview(Request $request)
    {

        $blog_id = $request->route('id');
        $request->merge(['blog_id' => $blog_id]);
        if ($request->ajax()) {

            $request = $this->__sanitize($request);
            $validationErrors = $this->__validate($request, $blog_id);
            if ($validationErrors) {
                return response()->json([
                    'success' => false,
                    'errors' => $validationErrors,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => __('common::validation.success'),
            ]);
        }
        try {
            $blog = (object) $request->all();

            $catID = $blog->cat_id ?? '';

            $relatedBlogs = !empty($catID) ? BlogHelper::getBlogData(null, $catID, RELATED_BLOG_LIMIT, false, $blog_id) : null;
            $blog->cat = BlogHelper::getBlogsCategory($catID);

            $preview = 'true';

            return response()->view('cms::blogs.detail', ['blog' => $blog, '$relatedBlogs' => $relatedBlogs,  'preview' => $preview], 200);
        } catch (\Exception $e) {
            Flash::error(__('common::messages.model_data_not_found', ['model' => __($this->langFile . '.singular')]))->important();

            return redirect($this->listRoute);

        }

    }

    // validation
    private function __validateImage($request, $field)
    {
        $attributes = __($this->langFile . '.fields');
        $rules = Blog::$rules;
        $rules = [$field => $rules[$field]];
        $this->validate($request, $rules, [], $attributes);
    }

    // Sanitize Inputs
    private function __sanitize($request)
    {
        $publish = $request->get('publish') == 'on' ? 1 : 2;
        $request->merge([
            'title' => removeString($request->get('title'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
        ]);

        return $request;
    }

    private function __validate($request, $id = null)
    {
        $rules = [
            'cat_id' => 'required|integer|exists:cms_blog_category,cat_id',
            'title' => 'required|string|max:191|unique:cms_blog,title',
            'slug' => 'nullable|string|max:191|unique:cms_blog,slug',
            'thumb_image' => 'nullable|string|max:100',
            'image' => 'nullable|string|max:100',
            'detail' => 'required|string',
            'video_url' => 'nullable|string|max:255|url',
            'display_date' => 'required|date',
            'publish_from' => 'required|nullable|date',
            'publish_to' => 'nullable|date|after:publish_from',
            'remarks' => 'nullable|string|max:65535',
            'publish' => 'required|integer|min:1|max:2',
        ];
        $messages = __('common::validation');
        $attributes = __($this->langFile . '.fields');
        if (!empty($id)) {
            $rules['title'] = $rules['title'] . ',' . $id . ',blog_id';
            $rules['slug'] = $rules['slug'] . ',' . $id . ',blog_id';
        } else {
            $rules['title'] .= ',NULL,blog_id';
            $rules['slug'] .= ',NULL,blog_id';
        }
        $rules['title'] .= ',cat_id,' . $request->cat_id;
        $rules['slug'] .= ',cat_id,' . $request->cat_id;
        $this->validate($request, $rules, $messages, $attributes);
    }
}
