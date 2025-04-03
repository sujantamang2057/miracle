<?php

namespace Modules\Sys\app\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Sys\app\Models\Role;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RoleDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->editColumn('name', function ($model) {
                return '<a href="' . route('sys.roles.edit', $model->id) . '">' . $model->name . '</a>';
            })
            ->editColumn('count', function ($model) {
                if ($model->name == ROLE_MASTER) {
                    return __('common::general.all');
                } else {
                    return $model->permissionCount();
                }
            })
            ->addColumn('action', 'sys::roles.datatables_actions')
            ->rawColumns(['name', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery()->select('roles.*');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('role-data-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('sys.rbac.role'))
            ->dom('Bfrt')
            ->pageLength(DEFAULT_PAGE_SIZE_ROLE)
            ->parameters([
                'buttons' => [
                    [
                        'html' => '<a class="btn btn-success" href="' . route('sys.roles.create') . '">' . __('sys::models/roles.btn.add_role') . '</a>',
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
            'action' => ['orderable' => false, 'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-2-col'],
            'name' => new Column(['title' => __('sys::models/roles.fields.name'), 'data' => 'name']),
            'count' => ['orderable' => false, 'title' => __('sys::models/roles.text.permissions_count'), 'searchable' => false, 'class' => 'permissions-count-col'],
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Role_' . date('YmdHis');
    }
}
