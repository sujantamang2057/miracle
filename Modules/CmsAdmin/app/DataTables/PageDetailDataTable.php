<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\Cmsadmin\app\Models\PageDetail;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PageDetailDataTable extends DataTable
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
            ->setRowId('detail_id')
            ->setRowAttr([
                'data-index' => function ($pageDetails) {
                    return $pageDetails->detail_id;
                },
                'data-position' => function ($pageDetails) {
                    return $pageDetails->show_order;
                },
            ])
            ->editColumn('show_order', function ($pageDetails) {
                return "<i class='fas fa-arrows-alt text-green sortable'></i>";
            })
            ->editColumn('image', function ($model) {
                return renderFancyboxImage(PAGE_FILE_DIR_NAME, $model->image, IMAGE_WIDTH_200, IMAGE_WIDTH_1200);
            })
            ->editColumn('publish', function ($model) {
                return manageRenderBsSwitchGrid('pageDetails.togglePublish', 'publish', $model->detail_id, $model->publish, 'cmsadmin.pageDetails.togglePublish', ['page' => $model->page_id]);
            })

            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->detail_id . '">';
            })
            ->addColumn('action', 'cmsadmin::page_details.datatables_actions')
            ->rawColumns(['action', 'selection', 'image', 'show_order', 'publish']);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PageDetail $model)
    {
        $page_id = request()->route('page') | 0;

        return $model::with('page')->where('page_id', $page_id)->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $page_id = request()->route('page');

        $buttons = [];
        $buttons[] = renderLinkButton(
            __('common::crud.back'),
            route('cmsadmin.pages.index'),
            'chevron-circle-left',
            'warning', 'mr-1'
        );
        if (checkCmsadminPermission('pages.multidata')) {
            $buttons[] = renderLinkButton(
                __('common::multidata.name'),
                route('cmsadmin.pages.multidata', [$page_id]),
                'clone',
                'secondary', 'mr-1'
            );
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();
        $bulkActions = [];
        if (checkCmsadminPermission('pageDetails.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.pageDetails.togglePublish', ['page' => $page_id]),
                'value' => 'toggle',
            ];
        }
        if (checkCmsadminPermission('pageDetails.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.pageDetails.destroy', ['page' => $page_id, 'pageDetail' => 0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_PAGE_DETAIL)
            ->parameters([
                'lengthMenu' => [
                    json_decode(BACKEND_PAGESIZES),
                    json_decode(BACKEND_PAGESIZES_LABEL),
                ],
                'rowReorder' => [
                    'selector' => 'tr td.drag-handle',
                    'update' => false,
                ],
                'responsive' => true,
                'columnDefs' => getColumnDefsArr([0, -1], [1, 1]),
                'dom' => getDatatableDOM(),
                'stateSave' => false,
                'order' => [[1, 'desc']],
                'buttons' => $buttons,
                'language' => [
                    'url' => getDataTableLanguageUrl(),
                ],
                'initComplete' => 'function() {
                    setColumnFilter(this.api());
                    saveRowReorder(this.api().table(), "' . route('cmsadmin.pageDetails.reorder', ['page' => $page_id]) . '");
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
            'action' => ['orderable' => false,  'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-2-col'],
            [
                'title' => '',
                'data' => 'show_order',
                'searchable' => false,
                'class' => 'text-center align-middle drag-handle drag-col',
            ],
            [
                'data' => 'selection',
                'title' => '<input type="checkbox" id="select-all">',
                'class' => 'text-center align-middle checkbox-col',
                'sortable' => false,
                'searchable' => false,
            ],
            'title' => new Column(['title' => __('common::multidata.fields.title'), 'data' => 'title', 'class' => 'filter_text']),
            'image' => new Column(['title' => __('common::multidata.fields.image'), 'data' => 'image']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'class' => 'status-col filter_publish']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'details_datatable_' . time();
    }
}
