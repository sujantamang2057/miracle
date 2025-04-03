<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\Cms\app\Components\Helpers\NewsHelper;
use Modules\CmsAdmin\app\DataTables\NewsDataTable;
use Modules\CmsAdmin\app\DataTables\NewsTrashDataTable;
use Modules\CmsAdmin\app\Models\News;
use Modules\CmsAdmin\app\Models\NewsCategory;
use Modules\CmsAdmin\app\Models\NewsDetail;
use Modules\CmsAdmin\app\Repositories\NewsRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class NewsController extends BackendController
{
    public function __construct(NewsRepository $newsRepo)
    {
        parent::getConstructFrontEnd();
        $this->moduleName = 'cmsadmin.news';
        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'multidata', 'saveMultidata', 'togglePublish', 'trashList', 'reorder', 'imageEdit', 'permanentdestroy']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            buildPermissionMiddleware($this->moduleName, ['imageEdit', 'imageUpdate']) => ['imageEdit', 'imageUpdate'],
            // Permission Check - Extract
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['permanentdestroy']) => ['permanentdestroy'],
            buildCanMiddleware($this->moduleName, ['multidata']) => ['multidata'],
            buildCanMiddleware($this->moduleName, ['saveMultidata']) => ['saveMultidata'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['trashList']) => ['trashList'],
            buildCanMiddleware($this->moduleName, ['restore']) => ['restore'],
            buildCanMiddleware($this->moduleName, ['reorder']) => ['reorder'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->repository = $newsRepo;
        $this->langFile = 'cmsadmin::models/news';
        $this->commonLangFile = 'common::crud';

        // define route for redirect
        $this->listRoute = route('cmsadmin.news.index');
        $this->trashListRoute = route('cmsadmin.news.trashList');
        $this->detailRouteName = 'cmsadmin.news.show';
        // image PATH
        $this->imageFilePath = storage_path(NEWS_FILE_PATH);
        // image DIMENSION
        $this->imageDimensions = json_decode(NEWS_FILE_DIMENSIONS, true);
    }

    /**
     * Display a listing of the News.
     */
    public function index(NewsDataTable $newsDataTable)
    {
        return $newsDataTable->render('cmsadmin::news.index');
    }

    /**
     * Display a listing of the Trashed News.
     */
    public function trashList(NewsTrashDataTable $newsTrashDataTable)
    {
        return $newsTrashDataTable->render('cmsadmin::news.trash');
    }

    /**
     * Show the form for creating a new News.
     */
    public function create()
    {
        $cmsCategoryList = NewsCategory::getNewsCategoryLists(null, true);

        return view('cmsadmin::news.create')
            ->with('id', null)
            ->with('publish', getOldData('publish'))
            ->with('cmsCategoryList', $cmsCategoryList);
    }

    /**
     * Store a newly created News in storage.
     */
    public function store(Request $request)
    {
        // sanitize and validate input
        $request = $this->__sanitize($request);
        $this->__validate($request);

        $input = $request->all();
        $news = $this->repository->create($input);

        // manage images
        if ($request->has('banner_image')) {
            $file = $request->banner_image;
            if (!empty($file)) {
                $this->__manageImageFile($file, $news, 'banner_image');
            }
        }

        if ($request->has('feature_image')) {
            $file = $request->feature_image;
            if (!empty($file)) {
                $this->__manageImageFile($file, $news, 'feature_image');
            }
        }

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.news.show', ['news' => $news->news_id]));
    }

    /**
     * Display the specified News.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);

        if ($mode == 'trash-restore') {
            if (!$news = News::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($news->trashed()) {
                return view('cmsadmin::news.show-trash')
                    ->with('news', $news)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('cmsadmin.news.show', ['news' => $news->news_id]));
            }
        } else {
            if (!$news = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('cmsadmin::news.show')
            ->with('news', $news)
            ->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified News.
     */
    public function edit($id)
    {
        if (!$news = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $news->banner_image_pre = $news->banner_image;
        $news->feature_image_pre = $news->feature_image;
        // get dropdown list
        $cmsCategoryList = NewsCategory::getNewsCategoryLists($news->category_id, false);

        return view('cmsadmin::news.edit')
            ->with('id', $news->news_id)
            ->with('publish', getOldData('publish', $news->publish))
            ->with('news', $news)
            ->with('cmsCategoryList', $cmsCategoryList);
    }

    /**
     * Update the specified News in storage.
     */
    public function update($id, Request $request)
    {
        if (!$news = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        // sanitize first
        $request = $this->__sanitize($request);
        $this->__validate($request, $news->news_id);

        $input = $request->all();
        $news = $this->repository->update($input, $id);

        // manage images
        if ($request->has('banner_image')) {
            $file = $request->banner_image;
            $bannerImagePre = $request->banner_image_pre;
            if (!empty($file) && $bannerImagePre != $file) {
                $this->__manageImageFile($file, $news, 'banner_image');
                // delete old image
                $this->__deleteImageFile($bannerImagePre);
            }
        }

        if ($request->has('feature_image')) {
            $file = $request->feature_image;
            $featureImagePre = $request->feature_image_pre;
            if (!empty($file) && $featureImagePre != $file) {
                $this->__manageImageFile($file, $news, 'feature_image');
                // delete old image
                $this->__deleteImageFile($featureImagePre);
            }
        }

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.news.show', $id));
    }

    // render image_update modal
    public function imageEdit($id, $field)
    {
        if (!$news = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        return view('cmsadmin::news.image_edit')
            ->with('news', $news)
            ->with('field', $field)
            ->with('id', $news->news_id);
    }

    public function imageUpdate($id, $field, Request $request)
    {
        if (!$news = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $this->__validateImage($request, $field);
        $news = $this->repository->update($request->all(), $id);

        // manage images
        if ($request->has($field)) {
            $file = $request->$field;
            $fieldPreName = $field . '_pre';
            $fieldPre = $request->$fieldPreName;
            if (!empty($file) && $fieldPre != $file) {
                $this->__manageImageFile($file, $news, $field);
                // delete old image
                $this->__deleteImageFile($fieldPre);
            }
        }

        Flash::success(__('common::messages.updated_image', ['image' => __($this->langFile . '.fields.' . $field)]))->important();

        return redirect(route('cmsadmin.news.index'));
    }

    // validation
    private function __validateImage($request, $field)
    {
        $attributes = __($this->langFile . '.fields');
        $rules = [
            'banner_image' => 'nullable|string|max:100',
            'feature_image' => 'nullable|string|max:100',
        ];
        $rules = [$field => $rules[$field]];
        $this->validate($request, $rules, [], $attributes);
    }

    public function multidata($id)
    {
        if (!$news = $this->findModel($id)) {
            return redirect($this->listRoute);
        }

        $newsDetails = $news->details;
        $multidata = [];
        if (!empty($newsDetails)) {
            foreach ($newsDetails as $key => $detail) {
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

        return view('cmsadmin::news.multidata')
            ->with('news', $news)
            ->with('multidata', $multidata);
    }

    public function saveMultiData($id, Request $request)
    {
        if (!$news = $this->findModel($id)) {
            return redirect($this->listRoute);
        }
        $newsDetails = $news->details;
        $rules = [
            'multidata.*.title' => 'required|string|max:255',
            'multidata.*.image' => 'nullable|string|max:255',
            'multidata.*.detail' => 'nullable|string|max:65535',
            'multidata.*.layout' => 'nullable|integer|min:1|max:4',
        ];
        $this->validate($request, $rules, ['required' => __('common::messages.is_required', ['field' => __('common::multidata.fields.title')])]);
        $multidata = $request->input('multidata');
        $oldItems = !empty($newsDetails) ? $newsDetails->pluck('detail_id')->toArray() : [];
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
                // save or update news detail
                $currentNewsDetail = $news->details()->updateOrCreate(['detail_id' => $detailId], $data);

                if (!empty($image) && $imagePre != $image) {
                    $this->__manageImageFile($image, $currentNewsDetail, 'image');
                    // delete old image
                    $this->__deleteImageFile($imagePre);
                }
            }
        }
        $unsafeItems = array_diff($oldItems, $safeItems);
        NewsDetail::find($unsafeItems)->each->delete();

        return redirect(route('cmsadmin.newsDetails.index', $id));
    }

    public function preview(Request $request)
    {

        $news_id = $request->route('id');
        $request->merge(['news_id' => $news_id]);
        if ($request->ajax()) {
            $request = $this->__sanitize($request);
            $validationErrors = $this->__validate($request, $news_id);
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
            $news = (object) $request->all();
            $catId = $news->category_id ?: '';
            $relatedNews = !empty($catId) ? NewsHelper::getNewsDetails(null, $catId, RELATED_NEWS_LIMIT, false, $news_id) : null;
            $news->category = NewsHelper::getNewsCategory($news->category_id);
            $preview = 'true';

            return response()->view('cms::news.detail', ['news' => $news, 'relatedNews' => $relatedNews, 'preview' => $preview], 200);
        } catch (\Exception $e) {
            Flash::error(__('common::messages.model_data_not_found', ['model' => __($this->langFile . '.singular')]))->important();

            return redirect($this->listRoute);

        }

    }

    // sanitize inputs
    private function __sanitize($request)
    {
        $publish = ($request->get('publish') == 'on') ? 1 : 2;
        $request->merge([
            'news_title' => removeString($request->get('news_title'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
        ]);

        return $request;
    }

    // Validate function
    private function __validate($request, $id = null)
    {
        $rules = [
            'news_title' => 'required|string|max:255|unique:cms_news,news_title',
            'slug' => 'nullable|string|max:255|unique:cms_news,slug',
            'category_id' => 'required|integer|exists:cms_news_category,category_id',
            'description' => 'required|string|max:65535',
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
            $rules['news_title'] = $rules['news_title'] . ',' . $id . ',news_id';
            $rules['slug'] = $rules['slug'] . ',' . $id . ',news_id';
        } else {
            $rules['news_title'] .= ',NULL,news_id';
            $rules['slug'] .= ',NULL,slug';
        }
        $rules['news_title'] .= ',category_id,' . $request->category_id;
        $rules['slug'] .= ',category_id,' . $request->category_id;

        $this->validate($request, $rules, $messages, $attributes);
    }
}
