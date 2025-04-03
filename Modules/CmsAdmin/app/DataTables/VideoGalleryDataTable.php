<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\VideoGallery;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class VideoGalleryDataTable extends DataTable
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
            ->editColumn('feature_image', function ($model) {
                return renderFancyboxImage(VIDEO_ALBUM_FILE_DIR_NAME, $model->feature_image, IMAGE_WIDTH_200);
            })
            ->editColumn('feature_image', function ($videoGallery) {
                $html = renderFancyboxImage(VIDEO_ALBUM_FILE_DIR_NAME, $videoGallery->feature_image, IMAGE_WIDTH_200);

                return checkCmsAdminPermissionList(['videoGalleries.imageEdit', 'videoGalleries.imageUpdate'])
                ? "<div class='d-flex'>$html<a title='" . __('common::crud.edit_image') . "' data-href='" . route('cmsadmin.videoGalleries.imageEdit', ['id' => $videoGallery->video_id, 'field' => 'feature_image']) . "' class='btn btn-sm text-primary imgUpdate'><i class='fas fa-pen'></i></a></div>"
                : "<div class='d-flex'>$html</div>";
            })
            ->editColumn('publish', function ($model) {
                return manageRenderBsSwitchGrid('videoGalleries.togglePublish', 'publish', $model->video_id, $model->publish, 'cmsadmin.videoGalleries.togglePublish', ['videoAlbum' => $model->album_id]);
            })
            ->editColumn('reserved', function ($model) {
                return manageRenderBsSwitchGrid('videoGalleries.toggleReserved', 'reserved', $model->video_id, $model->reserved, 'cmsadmin.videoGalleries.toggleReserved', ['videoAlbum' => $model->album_id]);
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->video_id . '">';
            })
            ->addColumn('action', 'cmsadmin::video_galleries.datatables_actions')
            ->rawColumns(['action', 'selection', 'publish', 'reserved', 'feature_image']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \app\Models\VideoGallery  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(VideoGallery $model)
    {
        $album_id = request()->route('videoAlbum') | 0;

        return $model::with('album')->where('album_id', $album_id)->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $album_id = request()->route('videoAlbum');

        $buttons = [];

        if (checkCmsAdminPermissionList(['videoGalleries.create', 'videoGalleries.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();
        $bulkActions = [];

        if (checkCmsAdminPermission('videoGalleries.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.videoGalleries.togglePublish', ['videoAlbum' => $album_id]),
                'value' => 'toggle',
            ];
        }
        if (checkCmsAdminPermission('videoGalleries.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.videoGalleries.destroy', ['videoAlbum' => $album_id, 'gallery' => 0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_VIDEO_GALLERY)
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
                'data' => 'show_order',
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
            'caption' => new Column(['title' => __('cmsadmin::models/video_galleries.fields.caption'), 'data' => 'caption', 'class' => 'filter_text']),
            'feature_image' => new Column(['title' => __('cmsadmin::models/video_galleries.fields.feature_image'), 'data' => 'feature_image', 'class' => 'image-col']),
            'published_date' => new Column(['title' => __('common::crud.fields.published_date'), 'data' => 'published_date', 'class' => 'date-col']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'class' => 'status-col filter_publish']),
            'reserved' => new Column(['title' => __('common::crud.fields.reserved'), 'data' => 'reserved', 'class' => 'status-col filter_reserved']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'video_galleries_datatable_' . time();
    }
}
