<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Post;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PostTrashDataTable extends DataTable
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
            ->editColumn('banner_image', function ($post) {
                return renderFancyboxImage(POST_FILE_DIR_NAME, $post->banner_image, IMAGE_WIDTH_200);
            })
            ->editColumn('feature_image', function ($post) {
                return renderFancyboxImage(POST_FILE_DIR_NAME, $post->feature_image, IMAGE_WIDTH_200);
            })
            ->editColumn('publish', '{{ getPublishText($publish) }}')
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->post_id . '">';
            })
            ->addColumn('action', 'cmsadmin::posts.datatables_trash_actions')
            ->rawColumns(['action', 'selection', 'publish', 'banner_image', 'feature_image']);

    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Post  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Post $model)
    {
        return $model::with(['category' => function ($query) {
            $query->withTrashed();
        }])->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $buttons = [];
        $buttons[] = getListButton(route('cmsadmin.posts.index'));
        $buttons[] = getResetButton();

        $bulkActions = [];
        if (checkCmsAdminPermission('posts.restore')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_restore_option'),
                'url' => route('cmsadmin.posts.restore', [0]),
                'value' => 'restore',
            ];
        }
        if (checkCmsAdminPermission('posts.permanentDestroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_permanent_delete_option'),
                'url' => route('cmsadmin.posts.permanentDestroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_POST)
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
                'data' => 'post_id',
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
            'post_title' => new Column(['title' => __('cmsadmin::models/posts.fields.post_title'), 'data' => 'post_title', 'class' => 'filter_text']),
            'category' => new Column(['title' => __('cmsadmin::models/posts.fields.category_id'), 'data' => 'category.category_name', 'class' => 'filter_text']),
            'banner_image' => new Column(['title' => __('cmsadmin::models/posts.fields.banner_image'), 'data' => 'banner_image', 'class' => 'image-col']),
            'feature_image' => new Column(['title' => __('cmsadmin::models/posts.fields.feature_image'), 'data' => 'feature_image', 'class' => 'image-col']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'searchable' => true, 'class' => 'status-col filter_publish']),

        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'posts_datatable_' . time();
    }
}
