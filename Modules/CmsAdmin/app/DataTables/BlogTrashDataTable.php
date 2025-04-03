<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Blog;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BlogTrashDataTable extends DataTable
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
            ->editColumn('image', function ($model) {
                return renderFancyboxImage(BLOG_FILE_DIR_NAME, $model->image, IMAGE_WIDTH_200);
            })
            ->editColumn('publish', '{{ getPublishText($publish) }}')
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->blog_id . '">';
            })
            ->addColumn('action', 'cmsadmin::blogs.datatables_trash_actions')
            ->rawColumns(['action', 'selection', 'image', 'publish']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \app\Models\Blog  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Blog $model)
    {

        return $model::with('cat:cat_id,cat_title')->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $buttons = [];
        $buttons[] = getListButton(route('cmsadmin.blogs.index'));
        $buttons[] = getResetButton();
        $bulkActions = [];

        if (checkCmsAdminPermission('blogs.restore')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_restore_option'),
                'url' => route('cmsadmin.blogs.restore', [0]),
                'value' => 'restore',
            ];
        }
        if (checkCmsAdminPermission('blogs.permanentDestroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_permanent_delete_option'),
                'url' => route('cmsadmin.blogs.permanentDestroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_BLOG)
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
                'data' => 'blog_id',
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
            'cat_id' => new Column(['title' => __('cmsadmin::models/blogs.fields.cat_id'), 'data' => 'cat.cat_title', 'class' => 'filter_text']),
            'title' => new Column(['title' => __('cmsadmin::models/blogs.fields.title'), 'data' => 'title', 'class' => 'filter_text']),
            'slug' => new Column(['title' => __('cmsadmin::models/blogs.fields.slug'), 'data' => 'slug', 'class' => 'filter_text']),
            'image' => new Column(['title' => __('cmsadmin::models/blogs.fields.image'), 'data' => 'image', 'searchable' => false, 'class' => 'image-col']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'class' => 'status-col-md filter_publish']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'blogs_datatable_' . time();
    }
}
