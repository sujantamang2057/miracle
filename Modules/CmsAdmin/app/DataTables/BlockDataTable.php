<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Block;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BlockDataTable extends DataTable
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
            ->setRowId('block_id')
            ->editColumn('block_name', function ($model) {
                return checkCmsAdminPermissionList(['blocks.edit', 'blocks.update']) ? '<a href="' . route('cmsadmin.blocks.edit', $model->block_id) . '">' . $model->block_name . '</a>' : $model->block_name;
            })
            ->editColumn('updated_at', function ($model) {
                return !empty($model->updated_at) ? ('<span class="tool-tip" title="' . ($model->user?->name) . '">' . dateFormatter($model->updated_at, DEFAULT_DATETIME_FORMAT) . '</span>') : null;
            })
            ->editColumn('publish', function ($model) {
                return manageRenderBsSwitchGrid('blocks.togglePublish', 'publish', $model->block_id, $model->publish, 'cmsadmin.blocks.togglePublish');
            })
            ->editColumn('reserved', function ($model) {
                return manageRenderBsSwitchGrid('blocks.toggleReserved', 'reserved', $model->block_id, $model->reserved, 'cmsadmin.blocks.toggleReserved');
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->block_id . '">';
            })
            ->addColumn('action', 'cmsadmin::blocks.datatables_actions')
            ->rawColumns(['action', 'selection', 'block_name', 'updated_at', 'publish', 'reserved']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Block  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Block $model)
    {
        return $model->newQuery()->with('user');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $buttons = [];

        if (checkCmsAdminPermissionList(['blocks.create', 'blocks.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();

        if (checkCmsAdminPermission('blocks.trashList')) {
            $buttons[] = getTrashButton(route('cmsadmin.blocks.trashList'));
        }
        $bulkActions = [];
        if (checkCmsAdminPermission('blocks.regenerate')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_regenerate_option'),
                'url' => route('cmsadmin.blocks.regenerate'),
                'value' => 'regenerate',
            ];
        }
        if (checkCmsAdminPermission('blocks.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.blocks.togglePublish'),
                'value' => 'toggle',
            ];
        }
        if (checkCmsAdminPermission('blocks.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.blocks.destroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_BLOCK)
            ->parameters([
                'lengthMenu' => [
                    json_decode(BACKEND_PAGESIZES),
                    json_decode(BACKEND_PAGESIZES_LABEL),
                ],
                // For Responsive Datatable
                'responsive' => true,
                'columnDefs' => getColumnDefsArr([0, -1], [1, 1]), // First Parameter target and Second
                // Parameter Priority
                'dom' => getDatatableDOM(),
                'stateSave' => false,
                'order' => [[5, 'desc'], [1, 'desc']],
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
            'action' => ['orderable' => false, 'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-4-col'],
            [
                'data' => 'block_id',
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
            'block_name' => new Column(['title' => __('cmsadmin::models/blocks.fields.block_name'), 'data' => 'block_name', 'class' => 'filter_text']),
            'filename' => new Column(['title' => __('cmsadmin::models/blocks.fields.filename'), 'data' => 'filename', 'class' => 'filter_text']),
            'updated_at' => new Column(['title' => __('common::crud.fields.updated_at'), 'data' => 'updated_at', 'class' => 'date-col filter_text']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'searchable' => true, 'class' => 'status-col filter_publish']),
            'reserved' => new Column(['title' => __('common::crud.fields.reserved'), 'data' => 'reserved', 'searchable' => true, 'class' => 'status-col filter_reserved']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'blocks_datatable_' . time();
    }
}
