<?php

namespace Modules\Sys\app\DataTables;

use Modules\Sys\app\Models\Sns;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SnsDataTable extends DataTable
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
            ->setRowId('sns_id')
            ->setRowAttr([
                'data-index' => function ($sns) {
                    return $sns->sns_id;
                },
                'data-position' => function ($sns) {
                    return $sns->show_order;
                },
            ])
            ->editColumn('title', function ($model) {
                return checkSysPermissionList(['sns.edit', 'sns.update']) ? '<a href="' . route('sys.sns.edit', $model->sns_id) . '">' . $model->title . '</a>' : $model->title;
            })
            ->editColumn('show_order', function ($banner) {
                return "<i class='fas fa-arrows-alt text-green sortable'></i>";
            })
            ->editColumn('icon', function ($sns) {
                $html = renderFancyboxImage(SNS_FILE_DIR_NAME, $sns->icon, IMAGE_WIDTH_100, IMAGE_WIDTH_200);

                return checkSysPermissionList(['sns.imageEdit', 'sns.imageUpdate'])
                ? "<div class='d-flex'>$html<a title='" . __('common::crud.edit_image') . "' data-href='" . route('sys.sns.imageEdit', ['id' => $sns->sns_id, 'field' => 'icon']) . "' class='btn btn-sm text-primary imgUpdate'><i class='fas fa-pen'></i></a></div>"
                : "<div class='d-flex'>$html</div>";
            })
            ->editColumn('publish', '{{renderBsSwitchGrid("publish", $sns_id, $publish, route("sys.sns.togglePublish"))}}')
            ->editColumn('reserved', '{{renderBsSwitchGrid("reserved", $sns_id, $reserved, route("sys.sns.toggleReserved"))}}')
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->sns_id . '">';
            })
            ->addColumn('action', 'sys::sns.datatables_actions')
            ->rawColumns(['action', 'title', 'publish', 'show_order', 'reserved', 'selection', 'icon']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \app\Models\Sns  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Sns $model)
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

        if (checkSysPermissionList(['sns.create', 'sns.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();

        $bulkActions = [];
        if (checkSysPermission('sns.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('sys.sns.togglePublish'),
                'value' => 'toggle',
            ];
        }
        if (checkSysPermission('sns.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('sys.sns.destroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_SNS)
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
                'order' => [[1, 'desc']],
                'stateSave' => false,
                'buttons' => $buttons,
                'language' => [
                    'url' => getDataTableLanguageUrl(),
                ],
                'initComplete' => "function() {
					saveRowReorder(this.api().table(), '" . route('sys.sns.reorder') . "');
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
            'action' => ['orderable' => false, 'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-3-col'],
            [
                'title' => '',
                'data' => 'show_order',
                'class' => 'text-center align-middle drag-handle drag-col',
            ],
            [
                'data' => 'sns_id',
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
            'title' => new Column(['title' => __('sys::models/sns.fields.title'), 'data' => 'title', 'class' => 'filter_text']),
            'icon' => new Column(['title' => __('sys::models/sns.fields.icon'), 'data' => 'icon', 'class' => 'image-col']),
            'class' => new Column(['title' => __('sys::models/sns.fields.class'), 'data' => 'class', 'class' => 'filter_text']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'class' => 'status-col filter_publish']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'sns_datatable_' . time();
    }
}
