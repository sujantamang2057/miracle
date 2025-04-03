<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Contact;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ContactDataTable extends DataTable
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
            ->editColumn('created_at', function ($model) {
                return !empty($model->created_at) ? dateFormatter($model->created_at, DEFAULT_DATETIME_FORMAT) : null;
            })
            ->editColumn('mail_sent_on', function ($model) {
                return !empty($model->mail_sent_on) ? dateFormatter($model->mail_sent_on, DEFAULT_DATETIME_FORMAT) : null;
            })
            ->addColumn('action', 'cmsadmin::contacts.datatables_actions')
            ->rawColumns(['action']);

    }

    /**
     * Get query source of dataTable.
     *
     * @param  \app\Models\Contact  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Contact $model)
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
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->pageLength(DEFAULT_PAGE_SIZE_CONTACT)
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
                'buttons' => [
                    [getReloadButton()],
                ],
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
            'created_at' => new Column(['title' => __('common::crud.fields.created_at'), 'data' => 'created_at', 'class' => 'date-col filter_text']),
            'from_email' => new Column(['title' => __('cmsadmin::models/contacts.fields.from_email'), 'data' => 'from_email', 'class' => 'filter_text']),
            'from_name' => new Column(['title' => __('cmsadmin::models/contacts.fields.from_name'), 'data' => 'from_name', 'class' => 'filter_text']),
            'mail_sent_on' => new Column(['title' => __('cmsadmin::models/contacts.fields.mail_sent_on'), 'data' => 'mail_sent_on', 'class' => 'date-col filter_text']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'contacts_datatable_' . time();
    }
}
