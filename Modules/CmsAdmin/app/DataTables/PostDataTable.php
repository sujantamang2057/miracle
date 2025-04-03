<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Post;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PostDataTable extends DataTable
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
            ->setRowId('post_id')
            ->setRowAttr([
                'data-index' => function ($post) {
                    return $post->post_id;
                },
                'data-position' => function ($post) {
                    return $post->show_order;
                },
            ])
            ->editColumn('category_id', function ($model) {
                return $model->category ? $model->category->category_name : '';
            })
            ->editColumn('post_title', function ($model) {
                return checkCmsAdminPermissionList(['posts.edit', 'posts.update']) ? '<a href="' . route('cmsadmin.posts.edit', $model->post_id) . '">' . $model->post_title . '</a>' . getfrontLinkButton($model, 'cms.posts.detail', $model->slug) : $model->post_title;
            })
            ->editColumn('show_order', function ($model) {
                return "<i class='fas fa-arrows-alt text-green sortable'></i>";
            })
            ->editColumn('banner_image', function ($post) {
                $html = renderFancyboxImage(POST_FILE_DIR_NAME, $post->banner_image, IMAGE_WIDTH_200);

                return checkCmsAdminPermissionList(['posts.imageEdit', 'posts.imageUpdate'])
                ? "<div class='d-flex'>$html<a title='" . __('common::crud.edit_image') . "' data-href='" . route('cmsadmin.posts.imageEdit', ['id' => $post->post_id, 'field' => 'banner_image']) . "' class='btn btn-sm text-primary imgUpdate'><i class='fas fa-pen'></i></a></div>"
                : "<div class='d-flex'>$html</div>";
            })
            ->editColumn('feature_image', function ($post) {
                $html = renderFancyboxImage(POST_FILE_DIR_NAME, $post->feature_image, IMAGE_WIDTH_200);

                return checkCmsAdminPermissionList(['posts.imageEdit', 'posts.imageUpdate'])
                ? "<div class='d-flex'>$html<a title='" . __('common::crud.edit_image') . "' data-href='" . route('cmsadmin.posts.imageEdit', ['id' => $post->post_id, 'field' => 'feature_image']) . "' class='btn btn-sm text-primary imgUpdate'><i class='fas fa-pen'></i></a></div>"
                : "<div class='d-flex'>$html</div>";
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->post_id . '">';
            })
            ->editColumn('publish', function ($model) {
                return manageRenderBsSwitchGrid('posts.togglePublish', 'publish', $model->post_id, $model->publish, 'cmsadmin.posts.togglePublish');
            })
            ->addColumn('action', 'cmsadmin::posts.datatables_actions')
            ->rawColumns(['action', 'selection', 'post_title', 'show_order', 'publish', 'banner_image', 'feature_image']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Post  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Post $model)
    {
        return $model->orderBy('show_order', 'desc')->with('category');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $buttons = [];
        if (checkCmsAdminPermissionList(['posts.create', 'posts.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();
        if (checkCmsAdminPermission('posts.trashList')) {
            $buttons[] = getTrashButton(route('cmsadmin.posts.trashList'));
        }
        $bulkActions = [];
        if (checkCmsAdminPermission('posts.togglePublish')) {

            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.posts.togglePublish'),
                'value' => 'toggle',
            ];
        }
        if (checkCmsAdminPermission('posts.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.posts.destroy', [0]),
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
                'rowReorder' => [
                    'selector' => 'tr td.drag-handle',
                    'update' => false,
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
                'initComplete' => "function() {
                    saveRowReorder(this.api().table(), '" . route('cmsadmin.posts.reorder') . "');
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
            'action' => ['orderable' => false, 'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-5-col'],
            [
                'title' => '',
                'data' => 'show_order',
                'class' => 'text-center align-middle drag-handle drag-col',
            ],
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
            'category_id' => new Column(['title' => __('cmsadmin::models/posts.fields.category_id'), 'data' => 'category_id', 'class' => 'filter_post']),
            'banner_image' => new Column(['title' => __('cmsadmin::models/posts.fields.banner_image'), 'data' => 'banner_image', 'class' => 'category-image-col']),
            'feature_image' => new Column(['title' => __('cmsadmin::models/posts.fields.feature_image'), 'data' => 'feature_image', 'class' => 'category-image-col']),
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
