<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\CspHeader;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CspHeaderDataTable extends DataTable
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
            ->addColumn('action', 'cmsadmin::csp_headers.datatables_actions')
            ->editColumn('publish', function ($model) {
                if ($model->reserved == 1) {
                    return getYesNoText($model->publish);
                }

                return manageRenderBsSwitchGrid('cspHeaders.togglePublish', 'publish', $model->csp_id, $model->publish, 'cmsadmin.cspHeaders.togglePublish');
            })
            ->rawColumns(['directive', 'publish', 'action']);

    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\CspHeader  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CspHeader $model)
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

        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();

        if (checkCmsAdminPermission('cspHeaders.regenerate')) {
            $buttons[] = getRegenerateButton();
            if ($this->shouldShowRegenerateButton()) {
                $buttons[] = "<span class='image-help-text'>" . __('common::general.need_to_regenerate') . '</span>';
            }
        }

        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->pageLength(DEFAULT_PAGE_SIZE_PAGE)
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
                'order' => [[0, 'desc']],
                'buttons' => $buttons,
                'language' => [
                    'url' => getDataTableLanguageUrl(),
                ],
                'initComplete' => 'function() {
                    setColumnFilter(this.api());
                }',
            ]);
    }

    protected function shouldShowRegenerateButton()
    {
        $filePath = base_path('config/constants-policy.php');

        if (!file_exists($filePath)) {
            return true;
        }

        $fileModifiedTime = filemtime($filePath);

        return CspHeader::where('updated_at', '>', date('Y-m-d H:i:s', $fileModifiedTime))->exists();
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'action' => ['orderable' => false, 'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-2-col'],

            'directive' => new Column(['title' => __('cmsadmin::models/csp_headers.fields.directive'), 'data' => 'directive']),
            'keyword' => new Column(['title' => __('cmsadmin::models/csp_headers.fields.keyword'), 'data' => 'keyword']),
            'value' => new Column(['title' => __('cmsadmin::models/csp_headers.fields.value'), 'data' => 'value']),
            'schema' => new Column(['title' => __('cmsadmin::models/csp_headers.fields.schema'), 'data' => 'schema']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish']),

        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'csp_headers_datatable_' . time();
    }
}
