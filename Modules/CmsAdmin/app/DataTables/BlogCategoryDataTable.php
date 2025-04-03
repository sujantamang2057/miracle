<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\BlogCategory;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BlogCategoryDataTable extends DataTable
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
                    return $model->cat_id;
                },
                'data-position' => function ($model) {
                    return $model->show_order;
                },
            ])
            ->editColumn('show_order', function ($model) {
                return "<i class='fas fa-arrows-alt text-green sortable'></i>";
            })
            ->editColumn('cat_title', function ($model) {
                return checkCmsAdminPermissionList(['blogCategories.edit', 'blogCategories.update']) ? '<a href="' . route('cmsadmin.blogCategories.edit', $model->cat_id) . '">' . $model->cat_title . '</a>' : $model->cat_title;
            })
            ->editColumn('cat_image', function ($blogCategory) {
                $html = renderFancyboxImage(BLOG_CATEGORY_FILE_DIR_NAME, $blogCategory->cat_image, IMAGE_WIDTH_200);

                return checkCmsAdminPermissionList(['blogCategories.imageEdit', 'blogCategories.imageUpdate'])
                ? "<div class='d-flex'>$html<a title='" . __('common::crud.edit_image') . "' data-href='" . route('cmsadmin.blogCategories.imageEdit', ['id' => $blogCategory->cat_id, 'field' => 'cat_image']) . "' class='btn btn-sm text-primary imgUpdate'><i class='fas fa-pen'></i></a></div>"
                : "<div class='d-flex'>$html</div>";
            })
            ->editColumn('publish', '{{renderBsSwitchGrid("publish", $cat_id, $publish, route("cmsadmin.blogCategories.togglePublish"))}}')
            ->addColumn('action', 'cmsadmin::blog_categories.datatables_actions')
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->cat_id . '">';
            })
            ->rawColumns(['action', 'cat_title', 'cat_image', 'publish', 'selection', 'show_order']);

    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\BlogCategory  $model
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

        if (checkCmsAdminPermissionList(['blogCategories.create', 'blogCategories.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();

        if (checkCmsAdminPermission('blogCategories.trashList')) {
            $buttons[] = getTrashButton(route('cmsadmin.blogCategories.trashList'));
        }
        $bulkActions = [];
        if (checkCmsAdminPermission('blogCategories.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.blogCategories.togglePublish'),
                'value' => 'toggle',
            ];
        }
        if (checkCmsAdminPermission('blogCategories.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.blogCategories.destroy', [0]),
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
                    saveRowReorder(this.api().table(), '" . route('cmsadmin.blogCategories.reorder') . "');
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
                'data' => 'cat_id',
                'searchable' => false,
                'visible' => false,
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
            'cat_image' => new Column(['title' => __('cmsadmin::models/blog_categories.fields.cat_image'), 'data' => 'cat_image', 'class' => 'image-col']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'class' => 'status-col filter_publish']),

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
