<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Seo;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SeoTrashDataTable extends DataTable
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
            ->editColumn('module_name', function ($model) {
                return checkCmsAdminPermissionList(['seos.edit', 'seos.update']) ? '<a href="' . route('cmsadmin.seos.edit', $model->id) . '">' . $model->module_name . '</a>' : $model->module_name;
            })
            ->editColumn('publish', '{{ getPublishText($publish) }}')
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->id . '">';
            })
            ->addColumn('action', 'cmsadmin::seos.datatables_trash_actions')
            ->rawColumns(['action', 'module_name', 'publish', 'selection']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \app\Models\Seo  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Seo $model)
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
        $buttons[] = getListButton(route('cmsadmin.seos.index'));
        $buttons[] = getResetButton();
        $bulkActions = [];
        if (checkCmsAdminPermission('seos.restore')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_restore_option'),
                'url' => route('cmsadmin.seos.restore', [0]),
                'value' => 'restore',
            ];
        }
        if (checkCmsAdminPermission('seos.permanentDestroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_permanent_delete_option'),
                'url' => route('cmsadmin.seos.permanentDestroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_SEO)
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
            'action' => ['orderable' => false,  'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-3-col'],
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
            'module_name' => new Column(['title' => __('cmsadmin::models/seos.fields.module_name'), 'data' => 'module_name', 'class' => 'filter_text']),
            'code' => new Column(['title' => __('cmsadmin::models/seos.fields.code'), 'data' => 'code', 'class' => 'filter_text']),
            'title' => new Column(['title' => __('cmsadmin::models/seos.fields.title'), 'data' => 'title', 'class' => 'filter_text']),
            'keyword' => new Column(['title' => __('cmsadmin::models/seos.fields.keyword'), 'data' => 'keyword', 'class' => 'filter_text']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'class' => 'status-col-md filter_publish']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'seos_datatable_' . time();
    }
}
