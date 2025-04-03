<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\PostCategory;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PostCategoryDataTable extends DataTable
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
            ->setRowAttr([
                'data-index' => function ($postCategory) {
                    return $postCategory->category_id;
                },
                'data-position' => function ($postCategory) {
                    return $postCategory->show_order;
                },
            ])
            ->editColumn('category_name', function ($model) {
                return checkCmsAdminPermissionList(['postCategories.edit', 'postCategories.update']) ? '<a href="' . route('cmsadmin.postCategories.edit', $model->category_id) . '">' . $model->category_name . '</a>' : $model->category_name;
            })
            ->editColumn('show_order', function ($model) {
                return "<i class='fas fa-arrows-alt text-green sortable'></i>";
            })
            ->editColumn('parent_category_id', function ($model) {
                return $model->parent ? $model->parent->category_name : '';
            })
            ->editColumn('category_image', function ($postCategory) {
                $html = renderFancyboxImage(POST_CATEGORY_FILE_DIR_NAME, $postCategory->category_image, IMAGE_WIDTH_200);

                return checkCmsAdminPermissionList(['postCategories.imageEdit', 'postCategories.imageUpdate'])
                ? "<div class='d-flex'>$html<a title='" . __('common::crud.edit_image') . "' data-href='" . route('cmsadmin.postCategories.imageEdit', ['id' => $postCategory->category_id, 'field' => 'category_image']) . "' class='btn btn-sm text-primary imgUpdate'><i class='fas fa-pen'></i></a></div>"
                : "<div class='d-flex'>$html</div>";
            })
            ->editColumn('publish', function ($model) {
                return manageRenderBsSwitchGrid('postCategories.togglePublish', 'publish', $model->category_id, $model->publish, 'cmsadmin.postCategories.togglePublish');
            })
            ->editColumn('reserved', function ($model) {
                return manageRenderBsSwitchGrid('postCategories.toggleReserved', 'reserved', $model->category_id, $model->reserved, 'cmsadmin.postCategories.toggleReserved');
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->category_id . '">';
            })
            ->addColumn('action', 'cmsadmin::post_categories.datatables_actions')
            ->rawColumns(['action', 'category_name', 'show_order', 'publish', 'reserved', 'selection', 'category_image']);

    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\PostCategory  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PostCategory $model)
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
        if (checkCmsAdminPermissionList(['postCategories.create', 'postCategories.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();
        if (checkCmsAdminPermission('postCategories.trashList')) {
            $buttons[] = getTrashButton(route('cmsadmin.postCategories.trashList'));
        }
        $bulkActions = [];
        if (checkCmsAdminPermission('postCategories.togglePublish')) {

            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.postCategories.togglePublish'),
                'value' => 'toggle',
            ];
        }
        if (checkCmsAdminPermission('postCategories.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.postCategories.destroy', [0]),
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
                    saveRowReorder(this.api().table(), '" . route('cmsadmin.postCategories.reorder') . "');
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
