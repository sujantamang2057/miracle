<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\Cmsadmin\app\Models\NewsDetail;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class NewsDetailDataTable extends DataTable
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
                'data-index' => function ($newsDetails) {
                    return $newsDetails->detail_id;
                },
                'data-position' => function ($newsDetails) {
                    return $newsDetails->show_order;
                },
            ])
            ->editColumn('show_order', function ($newsDetails) {
                return "<i class='fas fa-arrows-alt text-green sortable'></i>";
            })
            ->editColumn('image', function ($model) {
                return renderFancyboxImage(NEWS_FILE_DIR_NAME, $model->image, IMAGE_WIDTH_200, IMAGE_WIDTH_1200);
            })
            ->editColumn('publish', function ($model) {
                return manageRenderBsSwitchGrid('newsDetails.togglePublish', 'publish', $model->detail_id, $model->publish, 'cmsadmin.newsDetails.togglePublish', ['news' => $model->news_id]);
            })

            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->detail_id . '">';
            })
            ->addColumn('action', 'cmsadmin::news_details.datatables_actions')
            ->rawColumns(['action', 'selection', 'image', 'show_order', 'publish']);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(NewsDetail $model)
    {
        $news_id = request()->route('news') | 0;

        return $model::with('news')->where('news_id', $news_id)->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $news_id = request()->route('news');

        $buttons = [];
        $buttons[] = renderLinkButton(
            __('common::crud.back'),
            route('cmsadmin.news.index'),
            'chevron-circle-left',
            'warning', 'mr-1'
        );
        if (checkCmsadminPermission('news.multidata')) {
            $buttons[] = renderLinkButton(
                __('common::multidata.name'),
                route('cmsadmin.news.multidata', [$news_id]),
                'clone',
                'secondary', 'mr-1'
            );
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();
        $bulkActions = [];
        if (checkCmsadminPermission('newsDetails.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.newsDetails.togglePublish', ['news' => $news_id]),
                'value' => 'toggle',
            ];
        }
        if (checkCmsadminPermission('newsDetails.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.newsDetails.destroy', ['news' => $news_id, 'newsDetail' => 0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_NEWS_DETAIL)
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
                    saveRowReorder(this.api().table(), "' . route('cmsadmin.newsDetails.reorder', ['news' => $news_id]) . '");
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
