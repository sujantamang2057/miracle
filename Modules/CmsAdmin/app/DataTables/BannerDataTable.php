<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Banner;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BannerDataTable extends DataTable
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
            ->setRowId('banner_id')
            ->setRowAttr([
                'data-index' => function ($banner) {
                    return $banner->banner_id;
                },
                'data-position' => function ($banner) {
                    return $banner->show_order;
                },
            ])
            ->editColumn('show_order', function ($banner) {
                return "<i class='fas fa-arrows-alt text-green sortable'></i>";
            })
            ->editColumn('title', function ($model) {
                return checkCmsAdminPermissionList(['banners.edit', 'banners.update']) ? '<a href="' . route('cmsadmin.banners.edit', $model->banner_id) . '">' . $model->title . '</a>' : $model->title;
            })
            ->editColumn('pc_image', function ($banner) {
                $html = renderFancyboxImage(BANNER_FILE_DIR_NAME, $banner->pc_image, IMAGE_WIDTH_200);

                return checkCmsAdminPermissionList(['banners.imageEdit', 'banners.imageUpdate'])
                ? "<div class='d-flex'>$html<a title='" . __('common::crud.edit_image') . "' data-href='" . route('cmsadmin.banners.imageEdit', ['id' => $banner->banner_id, 'field' => 'pc_image']) . "' class='btn btn-sm text-primary imgUpdate'><i class='fas fa-pen'></i></a></div>"
                : "<div class='d-flex'>$html</div>";
            })
            ->editColumn('sp_image', function ($banner) {
                $html = renderFancyboxImage(BANNER_FILE_DIR_NAME, $banner->sp_image, IMAGE_WIDTH_200);

                return checkCmsAdminPermissionList(['banners.imageEdit', 'banners.imageUpdate'])
                ? "<div class='d-flex'>$html<a title='" . __('common::crud.edit_image') . "' data-href='" . route('cmsadmin.banners.imageEdit', ['id' => $banner->banner_id, 'field' => 'sp_image']) . "' class='btn btn-sm text-primary imgUpdate'><i class='fas fa-pen'></i></a></div>"
                : "<div class='d-flex'>$html</div>";
            })
            ->editColumn('publish', '{{renderBsSwitchGrid("publish", $banner_id, $publish, route("cmsadmin.banners.togglePublish"))}}')
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->banner_id . '">';
            })
            ->addColumn('action', 'cmsadmin::banners.datatables_actions')
            ->rawColumns(['action', 'title', 'publish', 'reserved', 'show_order', 'selection', 'pc_image', 'sp_image']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \app\Models\Banner  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Banner $model)
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

        if (checkCmsAdminPermissionList(['banners.create', 'banners.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();

        if (checkCmsAdminPermission('banners.trashList')) {
            $buttons[] = getTrashButton(route('cmsadmin.banners.trashList'));
        }
        $bulkActions = [];
        if (checkCmsAdminPermission('banners.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.banners.togglePublish'),
                'value' => 'toggle',
            ];
        }
        if (checkCmsAdminPermission('banners.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.banners.destroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_BANNER)
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
                    saveRowReorder(this.api().table(), '" . route('cmsadmin.banners.reorder') . "');
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
                'data' => 'banner_id',
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

            'title' => new Column(['title' => __('cmsadmin::models/banners.fields.title'), 'data' => 'title', 'class' => 'filter_text']),
            'pc_image' => new Column(['title' => __('cmsadmin::models/banners.fields.pc_image'), 'data' => 'pc_image', 'class' => 'image-col ']),
            'sp_image' => new Column(['title' => __('cmsadmin::models/banners.fields.sp_image'), 'data' => 'sp_image', 'class' => 'image-col ']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'searchable' => true, 'class' => 'status-col filter_publish']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'banners_datatable_' . time();
    }
}
