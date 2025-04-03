<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\NewsCategory;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class NewsCategoryDataTable extends DataTable
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
            ->setRowId('category_id')
            ->editColumn('category_name', function ($model) {
                return checkCmsAdminPermissionList(['newsCategories.edit', 'newsCategories.update']) ? '<a href="' . route('cmsadmin.newsCategories.edit', $model->category_id) . '">' . $model->category_name . '</a>' : $model->category_name;
            })
            ->setRowAttr([
                'data-index' => function ($newsCategory) {
                    return $newsCategory->category_id;
                },
                'data-position' => function ($newsCategory) {
                    return $newsCategory->show_order;
                },
            ])
            ->editColumn('parent_category_id', function ($model) {
                return $model->parent ? $model->parent->category_name : '';
            })
            ->editColumn('show_order', function ($model) {
                return "<i class='fas fa-arrows-alt text-green sortable'></i>";
            })
            ->editColumn('publish', function ($model) {
                return manageRenderBsSwitchGrid('newsCategories.togglePublish', 'publish', $model->category_id, $model->publish, 'cmsadmin.newsCategories.togglePublish');
            })
            ->editColumn('reserved', function ($model) {
                return manageRenderBsSwitchGrid('newsCategories.toggleReserved', 'reserved', $model->category_id, $model->reserved, 'cmsadmin.newsCategories.toggleReserved');
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->category_id . '">';
            })
            ->addColumn('action', 'cmsadmin::news_categories.datatables_actions')
            ->rawColumns(['action', 'category_name', 'publish', 'show_order', 'reserved', 'selection']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\NewsCategory  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(NewsCategory $model)
    {
        return $model->orderBy('show_order', 'desc')->with('parent');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $buttons = [];

        if (checkCmsAdminPermissionList(['newsCategories.create', 'newsCategories.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();
        if (checkCmsAdminPermission('newsCategories.trashList')) {
            $buttons[] = getTrashButton(route('cmsadmin.newsCategories.trashList'));
        }

        $bulkActions = [];
        if (checkCmsAdminPermission('newsCategories.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.newsCategories.togglePublish'),
                'value' => 'toggle',
            ];
        }
        if (checkCmsAdminPermission('newsCategories.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.newsCategories.destroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_NEWS_CATEGORY)
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
                    saveRowReorder(this.api().table(), '" . route('cmsadmin.newsCategories.reorder') . "');
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
                'data' => 'category_id',
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
            'category_name' => new Column(['title' => __('cmsadmin::models/news_categories.fields.category_name'), 'data' => 'category_name', 'class' => 'filter_text']),
            'parent_category_id' => new Column(['title' => __('cmsadmin::models/news_categories.fields.parent_category_id'), 'data' => 'parent_category_id', 'class' => 'filter_news']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'searchable' => true, 'class' => 'status-col filter_publish']),
            'reserved' => new Column(['title' => __('common::crud.fields.reserved'), 'data' => 'reserved', 'searchable' => true, 'class' => 'status-col filter_reserved']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'news_categories_datatable_' . time();
    }
}
