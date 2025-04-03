<?php

namespace Modules\Sys\app\DataTables;

use Modules\Sys\app\Models\User;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserTrashDataTable extends DataTable
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
            ->editColumn('created_at', function ($model) {
                return !empty($model->created_at) ? dateFormatter($model->created_at, DEFAULT_DATETIME_FORMAT) : null;
            })
            ->editColumn('updated_at', function ($model) {
                return !empty($model->updated_at) ? ('<span class="tool-tip" title="' . ($model->user?->name) . '">' . dateFormatter($model->updated_at, DEFAULT_DATETIME_FORMAT) . '</span>') : null;
            })
            ->editColumn('profile_image', function ($user) {
                return renderFancyboxImage(USER_FILE_DIR_NAME, $user->profile_image, IMAGE_WIDTH_200, IMAGE_WIDTH_200);
            })
            ->editColumn('active', '{{ getActiveText($active) }}')
            ->addColumn('roles', function (User $user) {
                return $user->roles()->first()?->name;
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->id . '">';
            })
            ->addColumn('action', 'sys::users.datatables_trash_actions')
            ->rawColumns(['action', 'selection', 'name', 'active', 'profile_image', 'updated_at']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \app\Models\User  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        if (!isLoggedInUserMasterRole()) {
            $roleId = auth()->user()->role_id;
            $model = $model::whereHas('roles', function ($query) use ($roleId) {
                $query->where('id', '>=', $roleId);
            });
        }

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
        $buttons[] = getListButton(route('sys.users.index'));
        $buttons[] = getResetButton();
        $bulkActions = [];
        if (checkSysPermission('users.restore')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_restore_option'),
                'url' => route('sys.users.restore', [0]),
                'value' => 'restore',
            ];
        }
        if (checkSysPermission('users.permanentDestroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_permanent_delete_option'),
                'url' => route('sys.users.permanentDestroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_USER)
            ->parameters([
                'lengthMenu' => [
                    json_decode(BACKEND_PAGESIZES),
                    json_decode(BACKEND_PAGESIZES_LABEL),
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
                'data' => 'id',
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
            'name' => new Column(['title' => __('sys::models/users.fields.name'), 'data' => 'name', 'class' => 'filter_text']),
            'email' => new Column(['title' => __('sys::models/users.fields.email'), 'data' => 'email', 'class' => 'filter_text']),
            'roles' => new Column(['title' => __('sys::models/users.fields.role'), 'data' => 'roles', 'class' => 'filter_roles']),
            'profile_image' => new Column(['title' => __('sys::models/users.fields.profile_image'), 'data' => 'profile_image', 'class' => 'image-col']),
            'updated_at' => new Column(['title' => __('common::crud.fields.updated_at'), 'data' => 'updated_at', 'class' => 'date-col filter_text']),
            'active' => new Column(['title' => __('sys::models/users.fields.active'), 'data' => 'active', 'class' => 'status-col filter_active']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'users_datatable_' . time();
    }
}
