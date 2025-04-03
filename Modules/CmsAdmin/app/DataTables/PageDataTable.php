<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Page;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PageDataTable extends DataTable
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
            ->setRowId('page_id')
            ->setRowAttr([
                'data-index' => function ($page) {
                    return $page->page_id;
                },
                'data-position' => function ($page) {
                    return $page->show_order;
                },
            ])
            ->editColumn('page_title', function ($model) {
                return checkCmsAdminPermissionList(['pages.edit', 'pages.update'])
                    ? '<a href="' . route('cmsadmin.pages.edit', $model->page_id) . '">' . $model->page_title . '</a>'
                    . getfrontLinkButton($model, 'url', $model->slug) : $model->page_title;
            })

            ->editColumn('show_order', function ($banner) {
                return "<i class='fas fa-arrows-alt text-green sortable'></i>";
            })
            ->editColumn('page_type', function ($page) {
                return getPageTypeText($page->page_type);
            })
            ->editColumn('updated_at', function ($model) {
                return !empty($model->updated_at) ? ('<span class="tool-tip" title="' . ($model->user?->name) . '">' . dateFormatter($model->updated_at, DEFAULT_DATETIME_FORMAT) . '</span>') : null;
            })
            ->editColumn('banner_image', function ($page) {
                $html = renderFancyboxImage(PAGE_FILE_DIR_NAME, $page->banner_image, IMAGE_WIDTH_200);

                return checkCmsAdminPermissionList(['pages.imageEdit', 'pages.imageUpdate'])
                ? "<div class='d-flex'>$html<a title='" . __('common::crud.edit_image') . "' data-href='" . route('cmsadmin.pages.imageEdit', ['id' => $page->page_id, 'field' => 'banner_image']) . "' class='btn btn-sm text-primary imgUpdate'><i class='fas fa-pen'></i></a></div>"
                : "<div class='d-flex'>$html</div>";
            })
            ->editColumn('publish', function ($model) {
                return manageRenderBsSwitchGrid('pages.togglePublish', 'publish', $model->page_id, $model->publish, 'cmsadmin.pages.togglePublish');
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->page_id . '">';
            })
            ->addColumn('action', 'cmsadmin::pages.datatables_actions')
            ->rawColumns(['action', 'page_title', 'publish', 'show_order', 'selection', 'updated_at', 'banner_image']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Page  $model
     * @return \Illuminate\Database\Eloquent\Builde
     */
    public function query(Page $model)
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

        if (checkCmsAdminPermissionList(['pages.create', 'pages.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();

        if (checkCmsAdminPermission('pages.trashList')) {
            $buttons[] = getTrashButton(route('cmsadmin.pages.trashList'));
        }
        $bulkActions = [];
        if (checkCmsAdminPermission('pages.regenerate')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_regenerate_option'),
                'url' => route('cmsadmin.pages.regenerate'),
                'value' => 'regenerate',
            ];
        }

        if (checkCmsAdminPermission('pages.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.pages.togglePublish'),
                'value' => 'toggle',
            ];
        }
        if (checkCmsAdminPermission('pages.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.pages.destroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_PAGE)
            ->parameters([
                'lengthMenu' => [
                    json_decode(BACKEND_PAGESIZES),
                    json_decode(BACKEND_PAGESIZES_LABEL),
                ],
                'rowReorder' => [
                    'selector' => 'tr td.drag-handle',
                    'update' => false,
                ],
                // For Responsive Datatable
                'responsive' => true,
                'columnDefs' => getColumnDefsArr([0, -1], [1, 1]), // First Parameter target and Second
                // Parameter Priority
                'dom' => getDatatableDOM(),
                'stateSave' => false,
                'order' => [[1, 'desc']],
                'buttons' => $buttons,
                'language' => [
                    'url' => getDataTableLanguageUrl(),
                ],
                'initComplete' => "function() {
                    saveRowReorder(this.api().table(), '" . route('cmsadmin.pages.reorder') . "');
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
            'action' => ['orderable' => false, 'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-6-col'],
            [
                'title' => '',
                'data' => 'show_order',
                'class' => 'text-center align-middle drag-handle drag-col',
            ],
            [
                'data' => 'page_id',
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
            'page_title' => new Column(['title' => __('cmsadmin::models/pages.fields.page_title'), 'data' => 'page_title', 'class' => 'filter_text']),
            'page_type' => new Column(['title' => __('cmsadmin::models/pages.fields.page_type'), 'data' => 'page_type', 'class' => 'filter_page_type']),
            'updated_at' => new Column(['title' => __('common::crud.fields.updated_at'), 'data' => 'updated_at', 'class' => 'date-col filter_text']),
            'banner_image' => new Column(['title' => __('cmsadmin::models/pages.fields.banner_image'), 'data' => 'banner_image', 'class' => 'category-image-col']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'class' => 'status-col filter_publish']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'pages_datatable_' . time();
    }
}
