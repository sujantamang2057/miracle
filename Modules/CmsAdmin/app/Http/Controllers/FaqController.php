<?php

namespace Modules\CmsAdmin\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Modules\CmsAdmin\app\DataTables\FaqDataTable;
use Modules\CmsAdmin\app\DataTables\FaqTrashDataTable;
use Modules\CmsAdmin\app\Models\Faq;
use Modules\CmsAdmin\app\Models\FaqCategory;
use Modules\CmsAdmin\app\Repositories\FaqRepository;
use Modules\Common\app\Http\Controllers\BackendController;

class FaqController extends BackendController
{
    public function __construct(FaqRepository $faqRepo)
    {
        $this->moduleName = 'cmsadmin.faqs';
        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'togglePublish', 'toggleReserved', 'trashList', 'reorder']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['togglePublish']) => ['togglePublish'],
            buildCanMiddleware($this->moduleName, ['toggleReserved']) => ['toggleReserved'],
            buildCanMiddleware($this->moduleName, ['trashList']) => ['trashList'],
            buildCanMiddleware($this->moduleName, ['restore']) => ['restore'],
            buildCanMiddleware($this->moduleName, ['reorder']) => ['reorder'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->repository = $faqRepo;
        $this->langFile = 'cmsadmin::models/faqs';

        // define route for redirect
        $this->listRoute = route('cmsadmin.faqs.index');
        $this->trashListRoute = route('cmsadmin.faqs.trashList');
        $this->detailRouteName = 'cmsadmin.faqs.show';
    }

    /**
     * Display a listing of the Faq.
     */
    public function index(FaqDataTable $faqDataTable)
    {
        return $faqDataTable->render('cmsadmin::faqs.index');
    }

    /**
     * Display a listing of the Trashed Faq.
     */
    public function trashList(FaqTrashDataTable $faqTrashDataTable)
    {
        return $faqTrashDataTable->render('cmsadmin::faqs.trash');
    }

    /**
     * Show the form for creating a new Faq.
     */
    public function create()
    {
        // get the faq category list for dropdown
        $faqCategoryList = FaqCategory::getFaqCategoryLists(null, true);

        return view('cmsadmin::faqs.create')
            ->with('id', null)
            ->with('faqCategoryList', $faqCategoryList)
            ->with('publish', getOldData('publish'))
            ->with('reserved', getOldData('reserved'));
    }

    /**
     * Store a newly created Faq in storage.
     */
    public function store(Request $request)
    {
        // sanitize request
        $request = $this->__sanitize($request);
        $this->__validate($request);
        $input = $request->all();

        $faq = $this->repository->create($input);

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.faqs.show', ['faq' => $faq->faq_id]));
    }

    /**
     * Display the specified Faq.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);
        if ($mode == 'trash-restore') {
            if (!$faq = faq::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($faq->trashed()) {
                return view('cmsadmin::faqs.show-trash')
                    ->with('faq', $faq)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('cmsadmin.faqs.show', ['faq' => $faq->faq_id]));
            }
        } else {
            if (!$faq = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }

        return view('cmsadmin::faqs.show')
            ->with('faq', $faq)
            ->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified Faq.
     */
    public function edit($id)
    {
        if (!($faq = $this->findModel($id))) {
            return redirect($this->listRoute);
        }

        // get the faq category list for dropdown
        $faqCategoryList = FaqCategory::getFaqCategoryLists($faq->faq_cat_id, true);

        return view('cmsadmin::faqs.edit')
            ->with('faq', $faq)
            ->with('id', $faq->faq_id)
            ->with('faqCategoryList', $faqCategoryList)
            ->with('publish', getOldData('publish', $faq->publish))
            ->with('reserved', getOldData('reserved', $faq->reserved));
    }

    /**
     * Update the specified Faq in storage.
     */
    public function update($id, Request $request)
    {
        if (!($faq = $this->findModel($id))) {
            return redirect($this->listRoute);
        }

        // sanitize request
        $request = $this->__sanitize($request);
        $this->__validate($request, $faq->faq_id);
        $input = $request->all();

        $faq = $this->repository->update($input, $id);

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('cmsadmin.faqs.show', $id));
    }

    // Sanitize Inputs
    private function __sanitize($request)
    {
        $publish = ($request->get('publish') == 'on') ? 1 : 2;
        $reserved = ($request->get('reserved') == 'on') ? 1 : 2;
        $request->merge([
            'question' => removeString($request->get('question'), json_decode(REPLACE_KEYWORDS_TITLE)),
            'publish' => $publish,
            'reserved' => $reserved,
        ]);

        return $request;
    }

    // validation rules
    private function __validate($request, $id = null)
    {
        $rules = [
            'faq_cat_id' => 'required|integer|exists:cms_faq_category,faq_cat_id',
            'question' => 'required|string|max:191|unique:cms_faq,question',
            'answer' => 'required|string|max:65535',
            'publish' => 'required|integer|min:1|max:2',
            'reserved' => 'required|integer|min:1|max:2',
        ];
        $messages = __('common::validation');
        $attributes = __($this->langFile . '.fields');
        if (!empty($id)) {
            $rules['question'] = $rules['question'] . ',' . $id . ',faq_id';
        } else {
            $rules['question'] .= ',NULL,faq_id';
        }
        $rules['question'] .= ',faq_cat_id,' . $request->faq_cat_id;
        $this->validate($request, $rules, $messages, $attributes);
    }
}
