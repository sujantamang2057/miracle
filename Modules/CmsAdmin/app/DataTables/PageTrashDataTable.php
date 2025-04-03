<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Page;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PageTrashDataTable extends DataTable
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
            ->editColumn('page_type', function ($page) {
                return getPageTypeText($page->page_type);
            })
            ->editColumn('updated_at', function ($model) {
                return !empty($model->updated_at) ? ('<span class="tool-tip" title="' . ($model->user?->name) . '">' . dateFormatter($model->updated_at, DEFAULT_DATETIME_FORMAT) . '</span>') : null;
            })
            ->editColumn('publish', '{{ getPublishText($publish) }}')
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->page_id . '">';
            })
            ->addColumn('action', 'cmsadmin::pages.datatables_trash_actions')
            ->rawColumns(['action', 'selection', 'publish', 'page_type', 'updated_at']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \app\Models\Page  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Page $model)
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
        $buttons[] = getListButton(route('cmsadmin.pages.index'));
        $buttons[] = getResetButton();
        $bulkActions = [];
        if (checkCmsAdminPermission('pages.restore')) {

            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_restore_option'),
                'url' => route('cmsadmin.pages.restore', [0]),
                'value' => 'restore',
            ];
        }
        if (checkCmsAdminPermission('pages.permanentDestroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_permanent_delete_option'),
                'url' => route('cmsadmin.pages.permanentDestroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_PAGE)
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
            'action' => ['orderable' => false, 'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-3-col'],
            [
                'data' => 'page_id',
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
            'page_title' => new Column(['title' => __('cmsadmin::models/pages.fields.page_title'), 'data' => 'page_title', 'class' => 'filter_text']),
            'slug' => new Column(['title' => __('cmsadmin::models/pages.fields.slug'), 'data' => 'slug', 'class' => 'filter_text']),
            'page_type' => new Column(['title' => __('cmsadmin::models/pages.fields.page_type'), 'data' => 'page_type', 'class' => 'filter_page_type']),
            'updated_at' => new Column(['title' => __('common::crud.fields.updated_at'), 'data' => 'updated_at', 'class' => 'date-col filter_text']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'class' => 'status-col-md filter_publish']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'pages_datatable_' . time();
    }
}
