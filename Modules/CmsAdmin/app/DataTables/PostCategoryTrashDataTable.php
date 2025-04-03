<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\PostCategory;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PostCategoryTrashDataTable extends DataTable
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
            ->editColumn('parent_category_id', function ($model) {
                return $model->parent ? $model->parent->category_name : '';
            })
            ->editColumn('category_image', function ($postCategory) {
                return renderFancyboxImage(POST_CATEGORY_FILE_DIR_NAME, $postCategory->category_image, IMAGE_WIDTH_200);
            })
            ->editColumn('publish', '{{ getPublishText($publish) }}')
            ->editColumn('reserved', '{{ getReservedText($reserved) }}')
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->category_id . '">';
            })
            ->addColumn('action', 'cmsadmin::post_categories.datatables_trash_actions')
            ->rawColumns(['action', 'selection', 'publish', 'reserved', 'category_image']);

    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\PostCategory  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PostCategory $model)
    {
        return $model->orderBy('show_order', 'desc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $buttons = [];
        $buttons[] = getListButton(route('cmsadmin.postCategories.index'));
        $buttons[] = getResetButton();
        $bulkActions = [];
        if (checkCmsAdminPermission('postCategories.restore')) {

            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_restore_option'),
                'url' => route('cmsadmin.postCategories.restore', [0]),
                'value' => 'restore',
            ];
        }
        if (checkCmsAdminPermission('postCategories.permanentDestroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_permanent_delete_option'),
                'url' => route('cmsadmin.postCategories.permanentDestroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_POST_CATEGORY)
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
            'category_name' => new Column(['title' => __('cmsadmin::models/post_categories.fields.category_name'), 'data' => 'category_name', 'class' => 'filter_text']),
            'parent_category_id' => new Column(['title' => __('cmsadmin::models/post_categories.fields.parent_category_id'), 'data' => 'parent_category_id', 'class' => 'filter_post']),
            'category_image' => new Column(['title' => __('cmsadmin::models/post_categories.fields.category_image'), 'data' => 'category_image', 'class' => 'category-image-col']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'class' => 'status-col filter_publish']),
            'reserved' => new Column(['title' => __('common::crud.fields.reserved'), 'data' => 'reserved', 'class' => 'status-col filter_reserved']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'post_categories_datatable_' . time();
    }
}
