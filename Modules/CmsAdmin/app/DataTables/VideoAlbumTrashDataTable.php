<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\VideoAlbum;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class VideoAlbumTrashDataTable extends DataTable
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
            ->editColumn('publish', '{{ getPublishText($publish) }}')
            ->editColumn('reserved', '{{ getReservedText($reserved) }}')
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->album_id . '">';
            })
            ->addColumn('action', 'cmsadmin::video_albums.datatables_trash_actions')
            ->rawColumns(['action', 'selection', 'publish', 'reserved']);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(VideoAlbum $model)
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
        $buttons[] = getListButton(route('cmsadmin.videoAlbums.index'));
        $buttons[] = getResetButton();
        $bulkActions = [];
        if (checkCmsAdminPermission('videoAlbums.restore')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_restore_option'),
                'url' => route('cmsadmin.videoAlbums.restore', [0]),
                'value' => 'restore',
            ];
        }
        if (checkCmsAdminPermission('videoAlbums.permanentDestroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_permanent_delete_option'),
                'url' => route('cmsadmin.videoAlbums.permanentDestroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_VIDEO_ALBUM)
            ->parameters([
                'lengthMenu' => [
                    json_decode(BACKEND_PAGESIZES),
                    json_decode(BACKEND_PAGESIZES_LABEL),
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
                'data' => 'album_id',
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
            'album_name' => new Column(['title' => __('cmsadmin::models/video_albums.fields.album_name'), 'data' => 'album_name', 'class' => 'filter_text']),
            'album_date' => new Column(['title' => __('cmsadmin::models/video_albums.fields.album_date'), 'data' => 'album_date', 'class' => 'date-col filter_text']),
            'slug' => new Column(['title' => __('cmsadmin::models/video_albums.fields.slug'), 'data' => 'slug', 'class' => 'filter_text']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'class' => 'status-col filter_publish']),
            'reserved' => new Column(['title' => __('common::crud.fields.reserved'), 'data' => 'reserved', 'class' => 'status-col filter_reserved']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'video_albums_datatable_' . time();
    }
}
