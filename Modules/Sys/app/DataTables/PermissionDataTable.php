<?php

namespace Modules\Sys\app\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Sys\app\Models\Permission;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PermissionDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Permission $model): QueryBuilder
    {
        return $model->newQuery()->select('permissions.*');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('permission-data-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('sys.rbac.permission'))
            ->dom('Bfrt<"row align-items-center justify-content-between p-2"<""l><""i><""p>>')
            ->pageLength(DEFAULT_PAGE_SIZE_PERMISSION)
            ->parameters([
                'lengthMenu' => [
                    json_decode(BACKEND_PAGESIZES),
                    json_decode(BACKEND_PAGESIZES_LABEL),
                ],
                'stateSave' => false,
                'order' => [[0, 'desc']],
                'buttons' => [
                    [
                        'html' => '<a class="btn btn-secondary no-corner mr-2" href="' . route('sys.permissions.scanRoutePermissions') . '">' . __('sys::models/permissions.btn.scan_permissions') . '</a>',
                    ],
                ],
                'language' => [
                    'url' => getDataTableLanguageUrl(),
                ],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            [
                'data' => 'id',
                'searchable' => false,
                'visible' => false,
                'orderable' => true,
            ],
            'name' => new Column(['title' => __('sys::models/permissions.fields.name'), 'data' => 'name']),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Permission_' . date('YmdHis');
    }
}
