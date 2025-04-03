<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Blog;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BlogDataTable extends DataTable
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
            ->setRowAttr([
                'data-index' => function ($model) {
                    return $model->blog_id;
                },
                'data-position' => function ($model) {
                    return $model->show_order;
                },
            ])
            ->editColumn('cat_id', function ($model) {
                return $model->cat ? $model->cat->cat_title : '';
            })
            ->editColumn('show_order', function ($model) {
                return "<i class='fas fa-arrows-alt text-green sortable'></i>";
            })
            ->editColumn('title', function ($model) {
                return checkCmsAdminPermissionList(['blogs.edit', 'blogs.update']) ? '<a href="' . route('cmsadmin.blogs.edit', $model->blog_id) . '">' . $model->title . '</a>' . getfrontLinkButton($model, 'cms.blogs.detail', $model->slug) : $model->title;
            })
            ->editColumn('image', function ($model) {
                $html = renderFancyboxImage(BLOG_FILE_DIR_NAME, $model->image, IMAGE_WIDTH_200);

                return checkCmsAdminPermissionList(['blogs.imageEdit', 'blogs.imageUpdate'])
                ? "<div class='d-flex'>$html<a title='" . __('common::crud.edit_image') . "' data-href='" . route('cmsadmin.blogs.imageEdit', ['id' => $model->blog_id, 'field' => 'image']) . "' class='btn btn-sm text-primary imgUpdate'><i class='fas fa-pen'></i></a></div>"
                : "<div class='d-flex'>$html</div>";
            })
            ->editColumn('publish', function ($model) {
                return manageRenderBsSwitchGrid('blogs.togglePublish', 'publish', $model->blog_id, $model->publish, 'cmsadmin.blogs.togglePublish');
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->blog_id . '">';
            })
            ->addColumn('action', 'cmsadmin::blogs.datatables_actions')
            ->rawColumns(['action', 'title', 'image', 'selection', 'publish', 'show_order']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \app\Models\Blog  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Blog $model)
    {
        return $model->orderBy('show_order', 'desc')->with('cat');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $buttons = [];

        if (checkCmsAdminPermissionList(['blogs.create', 'blogs.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();

        if (checkCmsAdminPermission('blogs.trashList')) {
            $buttons[] = getTrashButton(route('cmsadmin.blogs.trashList'));
        }
        $bulkActions = [];

        if (checkCmsAdminPermission('blogs.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.blogs.togglePublish'),
                'value' => 'toggle',
            ];
        }

        if (checkCmsAdminPermission('blogs.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.blogs.destroy', [0]),
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
                    saveRowReorder(this.api().table(), '" . route('cmsadmin.blogs.reorder') . "');
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
            'title' => new Column(['title' => __('cmsadmin::models/blogs.fields.title'), 'data' => 'title', 'class' => 'filter_text']),
            'cat_id' => new Column(['title' => __('cmsadmin::models/blogs.fields.cat_id'), 'data' => 'cat_id', 'class' => 'filter_blog']),
            'slug' => new Column(['title' => __('cmsadmin::models/blogs.fields.slug'), 'data' => 'slug', 'class' => 'filter_text']),
            'image' => new Column(['title' => __('cmsadmin::models/blogs.fields.image'), 'data' => 'image', 'searchable' => false, 'class' => 'category-image-col']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'class' => 'status-col filter_publish']),
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
