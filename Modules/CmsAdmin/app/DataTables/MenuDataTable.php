<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Menu;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MenuDataTable extends DataTable
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
            ->setRowId('menu_id')
            ->setRowAttr([
                'data-index' => function ($menu) {
                    return $menu->menu_id;
                },
                'data-position' => function ($menu) {
                    return $menu->show_order;
                },
            ])
            ->editColumn('title', function ($model) {
                return checkCmsAdminPermissionList(['menus.edit', 'menus.update']) ? '<a href="' . route('cmsadmin.menus.edit', $model->menu_id) . '">' . $model->title . '</a>' : $model->title;
            })
            ->editColumn('show_order', function ($banner) {
                return "<i class='fas fa-arrows-alt text-green sortable'></i>";
            })
            ->editColumn('parent_id', function ($model) {
                return $model->parent ? $model->parent->title : '';
            })
            ->editColumn('active', function ($model) {
                return manageRenderBsSwitchGrid('menus.toggleActive', 'active', $model->menu_id, $model->active, 'cmsadmin.menus.toggleActive');
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->menu_id . '">';
            })
            ->addColumn('action', 'cmsadmin::menus.datatables_actions')
            ->rawColumns(['action', 'title', 'active', 'show_order', 'selection']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Menu  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Menu $model)
    {
        return $model->orderBy('show_order', 'desc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $buttons = [];

        if (checkCmsAdminPermissionList(['menus.create', 'menus.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();

        if (checkCmsAdminPermission('menus.trashList')) {
            $buttons[] = getTrashButton(route('cmsadmin.menus.trashList'));
        }
        $bulkActions = [];
        if (checkCmsAdminPermission('menus.toggleActive')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_active_toggle_option'),
                'url' => route('cmsadmin.menus.toggleActive'),
                'value' => 'toggle',
            ];
        }
        if (checkCmsAdminPermission('menus.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.menus.destroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_MENU)
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
                    saveRowReorder(this.api().table(), '" . route('cmsadmin.menus.reorder') . "');
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
                'data' => 'menu_id',
                'searchable' => false,
                'visible' => false,
            ],
            [
                'data' => 'selection',
                'title' => '<input type="checkbox" id="select-all">',
                'class' => 'text-center checkbox-col align-middle',
                'sortable' => false,
                'searchable' => false,
            ],

            'title' => new Column(['title' => __('cmsadmin::models/menus.fields.title'), 'data' => 'title', 'class' => 'filter_text']),
            'parent_id' => new Column(['title' => __('cmsadmin::models/menus.fields.parent_id'), 'data' => 'parent_id', 'class' => 'filter_menu']),
            'url' => new Column(['title' => __('cmsadmin::models/menus.fields.url'), 'data' => 'url', 'class' => 'filter_text']),
            'active' => new Column(['title' => __('cmsadmin::models/menus.fields.active'), 'data' => 'active', 'class' => 'status-col filter_active', 'searchable' => true]),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'menus_datatable_' . time();
    }
}
