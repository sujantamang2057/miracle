<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\Cms\app\Components\Helpers\PostHelper;
use Modules\CmsAdmin\app\DataTables\PostDataTable;
use Modules\CmsAdmin\app\DataTables\PostTrashDataTable;
use Modules\CmsAdmin\app\Models\Post;
use Modules\CmsAdmin\app\Models\PostCategory;
use Modules\CmsAdmin\app\Models\PostDetail;
use Modules\CmsAdmin\app\Repositories\PostRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class PostController extends BackendController
{
    public function __construct(PostRepository $postRepo)
    {
        parent::getConstructFrontEnd();
        $this->moduleName = 'cmsadmin.posts';
        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'multidata', 'saveMultidata', 'togglePublish', 'trashList', 'imageEdit']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            buildPermissionMiddleware($this->moduleName, ['imageEdit', 'imageUpdate']) => ['imageEdit', 'imageUpdate'],

            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['restore']) => ['restore'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['multidata']) => ['multidata'],
            buildCanMiddleware($this->moduleName, ['saveMultidata']) => ['saveMultidata'],
            buildCanMiddleware($this->moduleName, ['trashList']) => ['trashList'],
            buildCanMiddleware($this->moduleName, ['removeImage']) => ['removeImage'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->langFile = 'cmsadmin::models/posts';
        $this->commonLangFile = 'common::crud';
        $this->repository = $postRepo;
        // define route for redirect
        $this->listRoute = route('cmsadmin.posts.index');
        $this->detailRouteName = 'cmsadmin.posts.show';
        $this->trashListRoute = route('cmsadmin.posts.trashList');

        // image PATH
        $this->imageFilePath = storage_path(POST_FILE_PATH);
        // image DIMENSION
        $this->imageDimensions = json_decode(POST_FILE_DIMENSIONS, true);
    }

    /**
     * Display a listing of the Post.
     */
    public function index(PostDataTable $postDataTable)
    {
        return $postDataTable->render('cmsadmin::posts.index');
    }

    public function trashList(PostTrashDataTable $postTrashDataTable)
    {
        return $postTrashDataTable->render('cmsadmin::posts.trash');
    }

    /**
     * Show the form for creating a new Post.
     */
    public function create()
    {
        // get dropdown list
        $postCategory = new PostCategory;

        return view('cmsadmin::posts.create')
            ->with('id', null)
            ->with('publish', getOldData('publish'))
            ->with('postCategory', $postCategory);
    }

    /**
     * Store a newly created Post in storage.
     */
    public function store(Request $request)
    {
        // sanitize first
        $request = $this->__sanitize($request);
        $request['category_id'] = $request['Post']['category_id'];
        $this->__validate($request);

        $post = $this->repository->create($request->all());

        // manage images
        if ($request->has('banner_image')) {
            $file = $request->banner_image;
            if (!empty($file)) {
                $this->__manageImageFile($file, $post, 'banner_image');
            }
        }

        if ($request->has('feature_image')) {
            $file = $request->feature_image;
            if (!empty($file)) {
                $this->__manageImageFile($file, $post, 'feature_image');
            }
        }

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.posts.show', ['post' => $post->post_id]));
    }

    /**
     * Display the specified Post.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);

        if ($mode == 'trash-restore') {
            if (!$post = Post::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($post->trashed()) {
                return view('cmsadmin::posts.show-trash')
                    ->with('post', $post)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('cmsadmin.posts.show', ['post' => $post->post_id]));
            }
        } else {
            if (!$post = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('cmsadmin::posts.show')
            ->with('post', $post)
            ->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified Post.
     */
    public function edit($id)
    {
        if (!$post = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $post->banner_image_pre = $post->banner_image;
        $post->feature_image_pre = $post->feature_image;

        // get dropdown list
        $postCategory = new PostCategory;
        if (!empty($post->category_id)) {
            $postCategory = PostCategory::where('category_id', $post->category_id)->first();
        }

        return view('cmsadmin::posts.edit')
            ->with('id', $post->post_id)
            ->with('publish', getOldData('publish', $post->publish))
            ->with('post', $post)
            ->with('postCategory', $postCategory);
    }

    /**
     * Update the specified Post in storage.
     */
    public function update($id, Request $request)
    {
        if (!$post = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        // sanitize first
        $request = $this->__sanitize($request);
        $request['category_id'] = $request['Post']['category_id'];
        $this->__validate($request, $post->post_id);

        $post = $this->repository->update($request->all(), $id);

        // manage images
        if ($request->has('banner_image')) {
            $file = $request->banner_image;
            $bannerImagePre = $request->banner_image_pre;
            if (!empty($file) && $bannerImagePre != $file) {
                $this->__manageImageFile($file, $post, 'banner_image');
                // delete old image
                $this->__deleteImageFile($bannerImagePre);
            }
        }

        if ($request->has('feature_image')) {
            $file = $request->feature_image;
            $featureImagePre = $request->feature_image_pre;
            if (!empty($file) && $featureImagePre != $file) {
                $this->__manageImageFile($file, $post, 'feature_image');
                // delete old image
                $this->__deleteImageFile($featureImagePre);
            }
        }

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.posts.show', $id));
    }

    public function multidata($id)
    {
        if (!$post = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $postDetails = $post->details;
        $multidata = [];
        if (!empty($postDetails)) {
            foreach ($postDetails as $key => $detail) {
                $multidata[$key]['detail_id'] = $detail->detail_id;
                $multidata[$key]['title'] = $detail->title;
                $multidata[$key]['layout'] = $detail->layout;
                $multidata[$key]['image_pre'] = $multidata[$key]['image'] = $detail->image;
                $multidata[$key]['detail'] = $detail->detail;
                $multidata[$key]['publish'] = $detail->publish;
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

        return view('cmsadmin::posts.multidata')
            ->with('post', $post)
            ->with('multidata', $multidata);
    }

    public function saveMultiData($id, Request $request)
    {
        if (!$post = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $postDetails = $post->details;
        $rules = [
            'multidata.*.title' => 'required|string|max:255',
            'multidata.*.image' => 'nullable|string|max:255',
            'multidata.*.detail' => 'nullable|string|max:65535',
            'multidata.*.layout' => 'nullable|integer|min:1|max:4',
        ];
        $this->validate($request, $rules, ['required' => __('common::messages.is_required', ['field' => __('common::multidata.fields.title')])]);
        $multidata = $request->input('multidata');
        $oldItems = !empty($postDetails) ? $postDetails->pluck('detail_id')->toArray() : [];
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
                // save or update post detail
                $currentPostDetail = $post->details()->updateOrCreate(['detail_id' => $detailId], $data);

                if (!empty($image) && $imagePre != $image) {
                    $this->__manageImageFile($image, $currentPostDetail, 'image');
                    // delete old image
                    $this->__deleteImageFile($imagePre);
                }
            }
        }
        $unsafeItems = array_diff($oldItems, $safeItems);
        PostDetail::find($unsafeItems)->each->delete();

        return redirect(route('cmsadmin.postDetails.index', $id));
    }

    // render model
    public function imageEdit($id, $field)
    {
        if (!$post = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('cmsadmin::posts.image_edit')
            ->with('post', $post)
            ->with('field', $field)
            ->with('id', $post->post_id);
    }

    public function imageUpdate($id, $field, Request $request)
    {
        if (!$post = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $this->__validateImage($request, $field);
        $post = $this->repository->update($request->all(), $id);

        // manage images
        if ($request->has($field)) {
            $file = $request->$field;
            $fieldPreName = $field . '_pre';
            $fieldPre = $request->$fieldPreName;
            if (!empty($file) && $fieldPre != $file) {
                $this->__manageImageFile($file, $post, $field);
                // delete old image
                $this->__deleteImageFile($fieldPre);
            }
        }

        Flash::success(__('common::messages.updated_image', ['image' => __($this->langFile . '.fields.' . $field)]))->important();

        return redirect(route('cmsadmin.posts.index'));
    }

    public function preview(Request $request)
    {
        $request['category_id'] = $request['Post']['category_id'] ?? '';
        $post_id = $request->route('id');
        $request->merge(['post_id' => $post_id]);
        if ($request->ajax()) {
            $request = $this->__sanitize($request);
            $validationErrors = $this->__validate($request, $post_id);
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
            $post = (object) $request->all();

            $catId = $post->category_id ?: '';
            $relatedNews = !empty($catId) ? PostHelper::getPostDetails(null, $catId, RELATED_NEWS_LIMIT, false, $post_id) : null;
            $post->category = PostHelper::getPostCategory($catId);
            $preview = 'true';

            return response()->view('cms::posts.detail', ['post' => $post, 'relatedNews' => $relatedNews, 'preview' => $preview], 200);

        } catch (\Exception $e) {
            Flash::error(__('common::messages.model_data_not_found', ['model' => __($this->langFile . '.singular')]))->important();

            return redirect($this->listRoute);

        }

    }

    // validation
    private function __validateImage($request, $field)
    {
        $attributes = __($this->langFile . '.fields');
        $rules = Post::$rules;
        $rules = [$field => $rules[$field]];
        $this->validate($request, $rules, [], $attributes);
    }

    // Sanitize Inputs
    private function __sanitize($request)
    {
        $publish = ($request->get('publish') == 'on') ? 1 : 2;
        $request->merge([
            'post_title' => removeString($request->get('post_title'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
        ]);

        return $request;
    }

    public function __validate($request, $id = null)
    {
        $rules = [
            'post_title' => 'required|string|max:191|unique:cms_post,post_title',
            'slug' => 'nullable|string|max:191|unique:cms_post,slug',
            'category_id' => 'required|integer|exists:cms_post_category,category_id',
            'description' => 'nullable|string|max:65535',
            'banner_image' => 'nullable|string|max:100',
            'feature_image' => 'nullable|string|max:100',
            'published_date' => 'required|date',
            'publish_date_from' => 'nullable|date',
            'publish_date_to' => 'nullable|date|after:publish_date_from',
            'publish' => 'required|integer|min:1|max:2',
        ];
        $messages = __('common::validation');

        $attributes1 = __($this->langFile . '.fields');
        $attributes2 = __($this->commonLangFile . '.fields');
        $attributes = $attributes1 + $attributes2;

        if (!empty($id)) {
            $rules['post_title'] = $rules['post_title'] . ',' . $id . ',post_id';
            $rules['slug'] = $rules['slug'] . ',' . $id . ',post_id';
        } else {
            $rules['post_title'] .= ',NULL,post_id';
            $rules['slug'] .= ',NULL,post_id';
        }
        $rules['post_title'] .= ',category_id,' . $request->category_id;
        $rules['slug'] .= ',category_id,' . $request->category_id;
        $this->validate($request, $rules, $messages, $attributes);
    }
}
