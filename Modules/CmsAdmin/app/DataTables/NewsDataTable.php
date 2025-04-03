<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\News;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class NewsDataTable extends DataTable
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
            ->setRowId('news_id')
            ->editColumn('news_title', function ($model) {
                return checkCmsAdminPermissionList(['news.edit', 'news.update']) ? '<a href="' . route('cmsadmin.news.edit', $model->news_id) . '">' . $model->news_title . '</a>' . getfrontLinkButton($model, 'cms.news.detail', $model->slug) : $model->news_title;
            })
            ->setRowAttr([
                'data-index' => function ($news) {
                    return $news->news_id;
                },
                'data-position' => function ($news) {
                    return $news->show_order;
                },
            ])
            ->editColumn('category_id', function ($model) {
                return $model->category ? $model->category->category_name : '';
            })
            ->editColumn('show_order', function ($banner) {
                return "<i class='fas fa-arrows-alt text-green sortable'></i>";
            })
            ->editColumn('banner_image', function ($banner) {
                $html = renderFancyboxImage(NEWS_FILE_DIR_NAME, $banner->banner_image, IMAGE_WIDTH_200);

                return checkCmsAdminPermissionList(['news.imageEdit', 'news.imageUpdate'])
                ? "<div class='d-flex'>$html<a title='" . __('common::crud.edit_image') . "' data-href='" . route('cmsadmin.news.imageEdit', ['id' => $banner->news_id, 'field' => 'banner_image']) . "' class='btn btn-sm text-primary imgUpdate'><i class='fas fa-pen'></i></a></div>"
                : "<div class='d-flex'>$html</div>";
            })
            ->editColumn('feature_image', function ($banner) {
                $html = renderFancyboxImage(NEWS_FILE_DIR_NAME, $banner->feature_image, IMAGE_WIDTH_200);

                return checkCmsAdminPermissionList(['news.imageEdit', 'news.imageUpdate'])
                ? "<div class='d-flex'>$html<a title='" . __('common::crud.edit_image') . "' data-href='" . route('cmsadmin.news.imageEdit', ['id' => $banner->news_id, 'field' => 'feature_image']) . "' class='btn btn-sm text-primary imgUpdate'><i class='fas fa-pen'></i></a></div>"
                : "<div class='d-flex'>$html</div>";
            })
            ->editColumn('publish', function ($model) {
                return manageRenderBsSwitchGrid('news.togglePublish', 'publish', $model->news_id, $model->publish, 'cmsadmin.news.togglePublish');
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->news_id . '">';
            })
            ->addColumn('action', 'cmsadmin::news.datatables_actions')
            ->rawColumns(['action', 'news_title', 'show_order', 'banner_image', 'feature_image', 'publish', 'selection']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\News  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(News $model)
    {
        return $model->orderBy('show_order', 'desc')->with('category');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $buttons = [];

        if (checkCmsAdminPermissionList(['news.create', 'news.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();

        if (checkCmsAdminPermission('news.trashList')) {
            $buttons[] = getTrashButton(route('cmsadmin.news.trashList'));
        }

        $bulkActions = [];

        if (checkCmsAdminPermission('news.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.news.togglePublish'),
                'value' => 'toggle',
            ];
        }
        if (checkCmsAdminPermission('news.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.news.destroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_NEWS)
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
                'stateSave' => false,
                'order' => [[1, 'desc']],
                'buttons' => $buttons,
                'language' => [
                    'url' => getDataTableLanguageUrl(),
                ],
                'initComplete' => "function() {
                    saveRowReorder(this.api().table(), '" . route('cmsadmin.news.reorder') . "');
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
            'action' => ['orderable' => false,  'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-5-col'],
            [
                'title' => '',
                'data' => 'show_order',
                'class' => 'text-center align-middle drag-handle drag-col',
            ],
            [
                'data' => 'news_id',
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
            'news_title' => new Column(['title' => __('cmsadmin::models/news.fields.news_title'), 'data' => 'news_title', 'class' => 'filter_text']),
            'category_id' => new Column(['title' => __('cmsadmin::models/news.fields.category_id'), 'data' => 'category_id', 'class' => 'filter_news']),
            'banner_image' => new Column(['title' => __('cmsadmin::models/news.fields.banner_image'), 'data' => 'banner_image', 'class' => 'category-image-col']),
            'feature_image' => new Column(['title' => __('cmsadmin::models/news.fields.feature_image'), 'data' => 'feature_image', 'class' => 'category-image-col']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'searchable' => true, 'class' => 'status-col filter_publish']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'news_datatable_' . time();
    }
}
