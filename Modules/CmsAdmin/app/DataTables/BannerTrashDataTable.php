<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Banner;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BannerTrashDataTable extends DataTable
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
            ->editColumn('pc_image', function ($banner) {
                return renderFancyboxImage(BANNER_FILE_DIR_NAME, $banner->pc_image, IMAGE_WIDTH_200);
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->banner_id . '">';
            })
            ->editColumn('sp_image', function ($banner) {
                return renderFancyboxImage(BANNER_FILE_DIR_NAME, $banner->sp_image, IMAGE_WIDTH_200);
            })
            ->editColumn('publish', '{{ getPublishText($publish) }}')
            ->editColumn('reserved', '{{ getReservedText($reserved) }}')
            ->addColumn('action', 'cmsadmin::banners.datatables_trash_actions')
            ->rawColumns(['action', 'selection', 'publish', 'reserved', 'pc_image', 'sp_image']);
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
        $buttons[] = getListButton(route('cmsadmin.banners.index'));
        $buttons[] = getResetButton();
        $bulkActions = [];
        if (checkCmsAdminPermission('banners.restore')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_restore_option'),
                'url' => route('cmsadmin.banners.restore', [0]),
                'value' => 'restore',
            ];
        }
        if (checkCmsAdminPermission('banners.permanentDestroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_permanent_delete_option'),
                'url' => route('cmsadmin.banners.permanentDestroy', [0]),
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
                'dom' => getDatatableDOM(),
                'responsive' => true,
                'columnDefs' => getColumnDefsArr([0, -1], [1, 1]), // First Parameter target and Second
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
            'action' => ['orderable' => false,  'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-3-col'],
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
            'pc_image' => new Column(['title' => __('cmsadmin::models/banners.fields.pc_image'), 'data' => 'pc_image', 'class' => 'image-col']),
            'sp_image' => new Column(['title' => __('cmsadmin::models/banners.fields.sp_image'), 'data' => 'sp_image', 'class' => 'image-col']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'searchable' => true, 'class' => 'status-col-md filter_publish']),
            'reserved' => new Column(['title' => __('common::crud.fields.reserved'), 'data' => 'reserved', 'searchable' => true, 'class' => 'status-col-md filter_reserved']),
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
