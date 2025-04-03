<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Menu;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MenuTrashDataTable extends DataTable
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
            ->editColumn('parent_id', function ($model) {
                if (!empty($model->parent) && !empty($model->parent->title)) {
                    return $model->parent->title;
                }

                return '';
            })
            ->filterColumn('parent_title', function ($query, $keyword) {
                $query->whereRaw('parent_menu.title  like ?', ["%{$keyword}%"]);
            })
            ->editColumn('active', '{{ getActiveText($active) }}')
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->menu_id . '">';
            })
            ->addColumn('action', 'cmsadmin::menus.datatables_trash_actions')
            ->rawColumns(['action', 'selection', 'title', 'active']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Menu  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Menu $model)
    {
        return $model::select('cms_menu.*', 'parent_menu.title as parent_title')
            ->leftJoin('cms_menu as parent_menu', 'parent_menu.menu_id', '=', 'cms_menu.parent_id')
            ->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $buttons = [];
        $buttons[] = getListButton(route('cmsadmin.menus.index'));
        $buttons[] = getResetButton();
        $bulkActions = [];
        if (checkCmsAdminPermission('menus.restore')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_restore_option'),
                'url' => route('cmsadmin.menus.restore', [0]),
                'value' => 'restore',
            ];
        }
        if (checkCmsAdminPermission('menus.permanentDestroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_permanent_delete_option'),
                'url' => route('cmsadmin.menus.permanentDestroy', [0]),
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
                'dom' => getDatatableDOM(),
                'responsive' => true,
                'columnDefs' => getColumnDefsArr([0, -1], [1, 1]), // First Parameter target and Second
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
            'action' => ['orderable' => false,  'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-3-col'],
            [
                'data' => 'menu_id',
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
            'title' => new Column(['title' => __('cmsadmin::models/menus.fields.title'), 'data' => 'title', 'class' => 'filter_text']),
            'parent_id' => new Column(['title' => __('cmsadmin::models/menus.fields.parent_id'), 'data' => 'parent_title', 'class' => 'filter_text']),
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
