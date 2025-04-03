<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\BlogCategory;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BlogCategoryTrashDataTable extends DataTable
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
            ->editColumn('cat_image', function ($blogCategory) {
                return renderFancyboxImage(BLOG_CATEGORY_FILE_DIR_NAME, $blogCategory->cat_image, IMAGE_WIDTH_200);
            })
            ->editColumn('publish', '{{ getPublishText($publish) }}')
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->cat_id . '">';
            })
            ->editColumn('action', 'cmsadmin::blog_categories.datatables_trash_actions')
            ->rawColumns(['action', 'selection', 'cat_image', 'publish']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \app\Models\BlogCategory  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(BlogCategory $model)
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
        $buttons[] = getListButton(route('cmsadmin.blogCategories.index'));
        $buttons[] = getResetButton();
        $bulkActions = [];
        if (checkCmsAdminPermission('blogCategories.restore')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_restore_option'),
                'url' => route('cmsadmin.blogCategories.restore', [0]),
                'value' => 'restore',
            ];
        }
        if (checkCmsAdminPermission('blogCategories.permanentDestroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_permanent_delete_option'),
                'url' => route('cmsadmin.blogCategories.permanentDestroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_BLOG_CATEGORY)
            ->parameters([
                'lengthMenu' => [
                    json_decode(BACKEND_PAGESIZES),
                    json_decode(BACKEND_PAGESIZES_LABEL),
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
            'action' => ['orderable' => false, 'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-3-col'],
            [
                'data' => 'cat_id',
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
            'cat_title' => new Column(['title' => __('cmsadmin::models/blog_categories.fields.cat_title'), 'data' => 'cat_title', 'class' => 'filter_text']),
            'cat_slug' => new Column(['title' => __('cmsadmin::models/blog_categories.fields.cat_slug'), 'data' => 'cat_slug', 'class' => 'filter_text']),
            'cat_image' => new Column(['title' => __('cmsadmin::models/blog_categories.fields.cat_image'), 'data' => 'cat_image', 'searchable' => false, 'class' => 'image-col']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'class' => 'status-col-md filter_publish']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'blog_categories_datatable_' . time();
    }
}
