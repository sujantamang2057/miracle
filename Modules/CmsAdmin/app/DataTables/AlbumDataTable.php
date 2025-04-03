<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Album;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AlbumDataTable extends DataTable
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
            ->setRowId('album_id')
            ->setRowAttr([
                'data-index' => function ($album) {
                    return $album->album_id;
                },
                'data-position' => function ($album) {
                    return $album->show_order;
                },
            ])
            ->editColumn('title', function ($model) {
                return checkCmsAdminPermissionList(['albums.edit', 'albums.update']) ? '<a href="' . route('cmsadmin.albums.edit', $model->album_id) . '">' . $model->title . '</a>' : $model->title;
            })
            ->editColumn('show_order', function ($banner) {
                return "<i class='fas fa-arrows-alt text-green sortable'></i>";
            })
            ->editColumn('cover_image_id', function ($model) {
                return renderFancyboxImage(ALBUM_FILE_DIR_NAME, $model->coverImage?->image_name, IMAGE_WIDTH_200);
            })
            ->editColumn('publish', function ($model) {
                return manageRenderBsSwitchGrid('albums.togglePublish', 'publish', $model->album_id, $model->publish, 'cmsadmin.albums.togglePublish');
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->album_id . '">';
            })
            ->addColumn('action', 'cmsadmin::albums.datatables_actions')
            ->rawColumns(['action', 'title', 'publish', 'show_order', 'selection', 'cover_image_id']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \app\Models\Album  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Album $model)
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

        if (checkCmsAdminPermissionList(['albums.create', 'albums.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();

        if (checkCmsAdminPermission('albums.trashList')) {
            $buttons[] = getTrashButton(route('cmsadmin.albums.trashList'));
        }

        $bulkActions = [];
        if (checkCmsAdminPermission('albums.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.albums.togglePublish'),
                'value' => 'toggle',
            ];
        }
        if (checkCmsAdminPermission('albums.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.albums.destroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_ALBUM)
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
                    saveRowReorder(this.api().table(), '" . route('cmsadmin.albums.reorder') . "');
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
            'action' => ['orderable' => false,  'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-4-col'],
            [
                'title' => '',
                'data' => 'show_order',
                'class' => 'text-center align-middle drag-handle drag-col',
            ],
            [
                'data' => 'album_id',
                'searchable' => false,
                'visible' => false,
            ],
            [
                'data' => 'selection',
                'title' => '<input type="checkbox" id="select-all">',
                'class' => 'text-center align-middle checkbox-col',
                'sortable' => false,
                'searchable' => false,
            ],
            'title' => new Column(['title' => __('cmsadmin::models/albums.fields.title'), 'data' => 'title', 'class' => 'filter_text']),
            'slug' => new Column(['title' => __('cmsadmin::models/albums.fields.slug'), 'data' => 'slug', 'class' => 'filter_text']),
            'cover_image_id' => new Column(['title' => __('cmsadmin::models/albums.fields.cover_image_id'), 'data' => 'cover_image_id', 'class' => 'image-col']),
            'image_count' => new Column(['title' => __('cmsadmin::models/albums.fields.image_count'), 'data' => 'image_count', 'class' => 'image-col']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'class' => 'status-col filter_publish']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'albums_datatable_' . time();
    }
}
