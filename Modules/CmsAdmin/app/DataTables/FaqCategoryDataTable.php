<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\FaqCategory;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FaqCategoryDataTable extends DataTable
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

        return $dataTable
            ->setRowId('faq_cat_id')
            ->setRowAttr([
                'data-index' => function ($model) {
                    return $model->faq_cat_id;
                },
                'data-position' => function ($model) {
                    return $model->show_order;
                },
            ])
            ->editColumn('faq_cat_name', function ($model) {
                return checkCmsAdminPermissionList(['faqCategories.edit', 'faqCategories.update']) ? '<a href="' . route('cmsadmin.faqCategories.edit', $model->faq_cat_id) . '">' . $model->faq_cat_name . '</a>' : $model->faq_cat_name;
            })
            ->editColumn('show_order', function ($banner) {
                return "<i class='fas fa-arrows-alt text-green sortable'></i>";
            })
            ->editColumn('faq_cat_image', function ($faqCategory) {
                $html = renderFancyboxImage(FAQ_CATEGORY_FILE_DIR_NAME, $faqCategory->faq_cat_image, IMAGE_WIDTH_200);

                return checkCmsAdminPermissionList(['faqCategories.imageEdit', 'faqCategories.imageUpdate'])
                ? "<div class='d-flex'>$html<a title='" . __('common::crud.edit_image') . "' data-href='" . route('cmsadmin.faqCategories.imageEdit', ['id' => $faqCategory->faq_cat_id, 'field' => 'faq_cat_image']) . "' class='btn btn-sm text-primary imgUpdate'><i class='fas fa-pen'></i></a></div>"
                : "<div class='d-flex'>$html</div>";
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->faq_cat_id . '">';
            })
            ->editColumn('publish', function ($model) {
                return manageRenderBsSwitchGrid('faqCategories.togglePublish', 'publish', $model->faq_cat_id, $model->publish, 'cmsadmin.faqCategories.togglePublish');
            })
            ->addColumn('action', 'cmsadmin::faq_categories.datatables_actions')
            ->rawColumns(['action', 'show_order', 'selection', 'faq_cat_name', 'faq_cat_image', 'publish']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\FaqCategory  $model
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

        if (checkCmsAdminPermissionList(['faqCategories.create', 'faqCategories.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();

        if (checkCmsAdminPermission('faqCategories.trashList')) {
            $buttons[] = getTrashButton(route('cmsadmin.faqCategories.trashList'));
        }

        $bulkActions = [];
        if (checkCmsAdminPermission('faqCategories.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.faqCategories.togglePublish'),
                'value' => 'toggle',
            ];
        }
        if (checkCmsAdminPermission('faqCategories.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.faqCategories.destroy', [0]),
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
                'rowReorder' => [
                    'selector' => 'tr td.drag-handle',
                    'update' => false,
                ],
                'dom' => getDatatableDOM(),
                'responsive' => true,
                'columnDefs' => getColumnDefsArr([0, -1], [1, 1]), // First Parameter target and Second Parameter Priority
                'order' => [[1, 'desc']],
                'stateSave' => false,
                'buttons' => $buttons,
                'language' => [
                    'url' => getDataTableLanguageUrl(),
                ],
                'initComplete' => "function() {
                    saveRowReorder(this.api().table(), '" . route('cmsadmin.faqCategories.reorder') . "');
                    setColumnFilter(this.api());
                }",
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
                'title' => '',
                'data' => 'show_order',
                'class' => 'text-center align-middle drag-handle drag-col',
            ],
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
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'searchable' => true, 'class' => 'status-col filter_publish']),
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
