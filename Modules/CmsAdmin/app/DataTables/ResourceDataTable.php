<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Resource;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ResourceDataTable extends DataTable
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
            ->editColumn('display_name', function ($model) {
                return checkCmsAdminPermissionList(['resources.edit', 'resources.update']) ? '<a href="' . route('cmsadmin.resources.edit', $model->resource_id) . '">' . $model->display_name . '</a>' : $model->display_name;
            })
            ->editColumn('publish', function ($model) {
                return manageRenderBsSwitchGrid('resources.togglePublish', 'publish', $model->resource_id, $model->publish, 'cmsadmin.resources.togglePublish');
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->resource_id . '">';
            })
            ->addColumn('action', 'cmsadmin::resources.datatables_actions')
            ->rawColumns(['action', 'display_name', 'publish', 'selection']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \app\Models\Resource  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Resource $model)
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
        if (checkCmsAdminPermissionList(['resources.create', 'resources.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();

        if (checkCmsAdminPermission('resources.trashList')) {
            $buttons[] = getTrashButton(route('cmsadmin.resources.trashList'));
        }
        $bulkActions = [];
        if (checkCmsAdminPermission('resources.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.resources.togglePublish'),
                'value' => 'toggle',
            ];
        }
        if (checkCmsAdminPermission('resources.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.resources.destroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_RESOURCE)
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
                'data' => 'resource_id',
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
            'display_name' => new Column(['title' => __('cmsadmin::models/resources.fields.display_name'), 'data' => 'display_name', 'class' => 'filter_text']),
            'file_name' => new Column(['title' => __('cmsadmin::models/resources.fields.file_name'), 'data' => 'file_name', 'class' => 'filter_text']),
            'file_size' => new Column(['title' => __('cmsadmin::models/resources.fields.file_size'), 'data' => 'file_size', 'class' => 'filter_text']),
            'file_type' => new Column(['title' => __('cmsadmin::models/resources.fields.file_type'), 'data' => 'file_type', 'searchable' => true, 'class' => 'filter_text']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'searchable' => true, 'class' => 'status-col filter_publish']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'resources_datatable_' . time();
    }
}
