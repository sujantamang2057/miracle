<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\FaqCategory;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FaqCategoryTrashDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param  mixed  $query  Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $query->onlyTrashed();

        return $dataTable
            ->editColumn('faq_cat_image', function ($model) {
                return renderFancyboxImage(FAQ_CATEGORY_FILE_DIR_NAME, $model->faq_cat_image, IMAGE_WIDTH_200);
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->faq_cat_id . '">';
            })
            ->editColumn('publish', '{{ getPublishText($publish) }}')
            ->addColumn('action', 'cmsadmin::faq_categories.datatables_trash_actions')
            ->rawColumns(['action', 'selection', 'faq_cat_image', 'publish']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \app\Models\FaqCategory  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(FaqCategory $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $buttons = [];
        $buttons[] = getListButton(route('cmsadmin.faqCategories.index'));
        $buttons[] = getResetButton();
        $bulkActions = [];
        if (checkCmsAdminPermission('faqCategories.restore')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_restore_option'),
                'url' => route('cmsadmin.faqCategories.restore', [0]),
                'value' => 'restore',
            ];
        }
        if (checkCmsAdminPermission('faqCategories.permanentDestroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_permanent_delete_option'),
                'url' => route('cmsadmin.faqCategories.permanentDestroy', [0]),
                'value' => 'delete',
            ];
        }

        if (!empty($bulkActions)) {
            $buttons[] = [
                'html' => renderBulkActions($bulkActions),
            ];
            $buttons[] = getApplyText();
        }

        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->pageLength(DEFAULT_PAGE_SIZE_FAQ_CATEGORY)
            ->parameters([
                'lengthMenu' => [
                    json_decode(BACKEND_PAGESIZES),
                    json_decode(BACKEND_PAGESIZES_LABEL),
                ],
                'dom' => getDatatableDOM(),
                'responsive' => true,
                'columnDefs' => getColumnDefsArr([0, -1], [1, 1]), // First Parameter target and Second Parameter Priority
                'stateSave' => false,
                'order' => [[1, 'desc']],
                'buttons' => $buttons,
                'language' => [
                    'url' => getDataTableLanguageUrl(),
                ],
                'initComplete' => 'function() {
                    setColumnFilter(this.api());
                }',
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'action' => ['orderable' => false,  'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-3-col'],
            [
                'data' => 'faq_cat_id',
                'searchable' => false,
                'visible' => false,
                'orderable' => true,
            ],
            [
                'data' => 'selection',
                'title' => '<input type="checkbox" id="select-all">',
                'class' => 'text-center align-middle checkbox-col',
                'sortable' => false,
                'searchable' => false,
            ],
            'faq_cat_name' => new Column(['title' => __('cmsadmin::models/faq_categories.fields.faq_cat_name'), 'data' => 'faq_cat_name', 'class' => 'filter_text']),
            'slug' => new Column(['title' => __('cmsadmin::models/faq_categories.fields.slug'), 'data' => 'slug', 'searchable' => true, 'class' => 'filter_text']),
            'faq_cat_image' => new Column(['title' => __('cmsadmin::models/faq_categories.fields.faq_cat_image'), 'data' => 'faq_cat_image', 'class' => 'category-image-col']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'class' => 'status-col-md  filter_publish']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'faq_categories_datatable_' . time();
    }
}
