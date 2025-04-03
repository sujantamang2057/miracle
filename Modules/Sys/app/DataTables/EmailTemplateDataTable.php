<?php

namespace Modules\Sys\app\DataTables;

use Modules\Sys\app\Models\EmailTemplate;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EmailTemplateDataTable extends DataTable
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
                return checkCmsAdminPermissionList(['emailTemplates.edit', 'emailTemplates.update']) ? '<a href="' . route('sys.emailTemplates.edit', $model->template_id) . '">' . $model->name . '</a>' : $model->name;

            })
            ->editColumn('updated_at', function ($model) {
                return !empty($model->updated_at) ? ('<span class="tool-tip" title="' . ($model->user?->name) . '">' . dateFormatter($model->updated_at, DEFAULT_DATETIME_FORMAT) . '</span>') : null;
            })
            ->editColumn('publish', function ($model) {
                return manageRenderBsSwitchGrid('emailTemplates.togglePublish', 'publish', $model->template_id, $model->publish, 'sys.emailTemplates.togglePublish');
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->template_id . '">';
            })
            ->addColumn('action', 'sys::email_templates.datatables_actions')
            ->rawColumns(['action', 'selection', 'name', 'updated_at', 'publish']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\EmailTemplate  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(EmailTemplate $model)
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

        if (checkSysPermissionList(['emailTemplates.create', 'emailTemplates.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();

        if (checkSysPermission('emailTemplates.trashList')) {
            $buttons[] = getTrashButton(route('sys.emailTemplates.trashList'));
        }
        $bulkActions = [];
        if (checkSysPermission('emailTemplates.regenerate')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_regenerate_option'),
                'url' => route('sys.emailTemplates.regenerate'),
                'value' => 'regenerate',
            ];
        }

        if (checkSysPermission('emailTemplates.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('sys.emailTemplates.togglePublish'),
                'value' => 'toggle',
            ];
        }
        if (checkSysPermission('emailTemplates.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('sys.emailTemplates.destroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_EMAIL_TEMPLATE)
            ->parameters([
                'lengthMenu' => [
                    json_decode(BACKEND_PAGESIZES),
                    json_decode(BACKEND_PAGESIZES_LABEL),
                ],
                // For Responsive Datatable
                'responsive' => true,
                'columnDefs' => getColumnDefsArr([0, -1], [1, 1]), // First Parameter target and Second
                // Parameter Priority
                'dom' => getDatatableDOM(),
                'order' => [[6, 'desc'], [1, 'desc']],
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
            'action' => ['orderable' => false, 'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-4-col'],
            [
                'data' => 'template_id',
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
            'name' => new Column(['title' => __('sys::models/email_templates.fields.name'), 'data' => 'name', 'class' => 'filter_text']),
            'mail_code' => new Column(['title' => __('sys::models/email_templates.fields.mail_code'), 'data' => 'mail_code', 'class' => 'filter_text']),
            'subject' => new Column(['title' => __('sys::models/email_templates.fields.subject'), 'data' => 'subject', 'class' => 'filter_text ']),
            'updated_at' => new Column(['title' => __('common::crud.fields.updated_at'), 'data' => 'updated_at', 'class' => 'date-col text-center filter_text']),
            'publish' => new Column(['title' => __('sys::models/email_templates.fields.publish'), 'data' => 'publish', 'class' => 'status-col filter_publish']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'email_templates_datatable_' . time();
    }
}
