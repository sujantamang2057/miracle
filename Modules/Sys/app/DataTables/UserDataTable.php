<?php

namespace Modules\Sys\app\DataTables;

use Modules\Sys\app\Models\User;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
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
            ->editColumn('name', function ($model) {
                return '<a href="' . route('sys.users.edit', $model->id) . '">' . $model->name . '</a>';
            })
            ->filterColumn('roles', function ($query, $keyword) {
                $query->whereHas('roles', function ($query) use ($keyword) {
                    $query->where('id', '=', "{$keyword}");
                });
            })
            ->editColumn('created_at', function ($model) {
                return !empty($model->created_at) ? dateFormatter($model->created_at, DEFAULT_DATETIME_FORMAT) : null;
            })
            ->editColumn('updated_at', function ($model) {
                return !empty($model->updated_at) ? ('<span class="tool-tip" title="' . getUserDataById($model->updated_by) . '">' . dateFormatter($model->updated_at, DEFAULT_DATETIME_FORMAT) . '</span>') : null;
            })
            ->editColumn('profile_image', function ($user) {
                $html = renderFancyboxImage(USER_FILE_DIR_NAME, $user->profile_image, IMAGE_WIDTH_200, IMAGE_WIDTH_200);

                return "<div class='d-flex'>$html<a title='" . __('common::crud.edit_image') . "' data-href='" . route('sys.users.imageEdit', ['id' => $user->id, 'field' => 'profile_image']) . "'class='btn btn-sm text-primary imgUpdate'><i class='fas fa-pen'></i></a></div>";
            })
            ->editColumn('active', '{!! ($id != 1 && auth()->user()->id != $id) ? manageRenderBsSwitchGrid("users.toggleActive", "active", $id, $active, "sys.users.toggleActive") : getActiveText($active) !!}')
            ->addColumn('roles', function (User $user) {
                return $user->roles()->first()?->name;
            })
            ->addColumn('action', 'sys::users.datatables_actions')
            ->rawColumns(['action', 'name', 'active', 'profile_image', 'updated_at']);
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

        if (checkSysPermissionList(['users.create', 'users.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();
        if (checkSysPermission('users.trashList')) {
            $buttons[] = getTrashButton(route('sys.users.trashList'));
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
                // For Responsive Datatable
                'responsive' => true,
                'columnDefs' => getColumnDefsArr([0, -1], [1, 1]), // First Parameter target and Second Parameter Priority
                'dom' => getDatatableDOM(),
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
            'action' => ['orderable' => false, 'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-4-col', 'width' => ''],
            [
                'data' => 'id',
                'searchable' => false,
                'visible' => false,
                'orderable' => true,
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
