<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Faq;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FaqDataTable extends DataTable
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
            ->setRowId('faq_id')
            ->setRowAttr([
                'data-index' => function ($model) {
                    return $model->faq_id;
                },
                'data-position' => function ($model) {
                    return $model->show_order;
                },
            ])
            ->editColumn('question', function ($model) {
                return checkCmsAdminPermissionList(['faqs.edit', 'faqs.update']) ? '<a href="' . route('cmsadmin.faqs.edit', $model->faq_id) . '">' . $model->question . '</a>' : $model->question;
            })
            ->editColumn('show_order', function ($banner) {
                return "<i class='fas fa-arrows-alt text-green sortable'></i>";
            })
            ->editColumn('faq_cat_id', function ($model) {
                return $model->faqCat ? $model->faqCat->faq_cat_name : 'N/A';
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->faq_id . '">';
            })
            ->editColumn('publish', function ($model) {
                return manageRenderBsSwitchGrid('faqs.togglePublish', 'publish', $model->faq_id, $model->publish, 'cmsadmin.faqs.togglePublish');
            })
            ->addColumn('action', 'cmsadmin::faqs.datatables_actions')
            ->rawColumns(['action', 'selection', 'show_order', 'question', 'publish']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\Faq  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Faq $model)
    {
        return $model->orderBy('show_order', 'desc')->with('faqCat');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {

        if (checkCmsAdminPermissionList(['faqs.create', 'faqs.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();

        if (checkCmsAdminPermission('faqs.trashList')) {
            $buttons[] = getTrashButton(route('cmsadmin.faqs.trashList'));
        }

        $bulkActions = [];
        if (checkCmsAdminPermission('faqs.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.faqs.togglePublish'),
                'value' => 'toggle',
            ];
        }
        if (checkCmsAdminPermission('faqs.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.faqs.destroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_FAQ)
            ->parameters([
                'lengthMenu' => [
                    json_decode(BACKEND_PAGESIZES),
                    json_decode(BACKEND_PAGESIZES_LABEL),
                ],
                'rowReorder' => [
                    'selector' => 'tr td.drag-handle',
                    'update' => false,
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
                'initComplete' => "function() {
                    saveRowReorder(this.api().table(), '" . route('cmsadmin.faqs.reorder') . "');
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
                'data' => 'faq_id',
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
            'faq_cat_id' => new Column(['title' => __('cmsadmin::models/faqs.fields.faq_cat_id'), 'data' => 'faq_cat_id', 'class' => 'filter_faqs']),
            'question' => new Column(['title' => __('cmsadmin::models/faqs.fields.question'), 'data' => 'question', 'class' => 'filter_text']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'searchable' => true, 'class' => 'status-col filter_publish']),

        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'faqs_datatable_' . time();
    }
}
