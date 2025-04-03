<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Gallery;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GalleryDataTable extends DataTable
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
            ->setRowId('image_id')
            ->setRowAttr([
                'data-index' => function ($gallery) {
                    return $gallery->image_id;
                },
                'data-position' => function ($gallery) {
                    return $gallery->show_order;
                },
            ])

            ->editColumn('show_order', function ($gallery) {
                return "<i class='fas fa-arrows-alt text-green sortable'></i>";
            })
            ->editColumn('caption', function ($model) {
                return checkCmsAdminPermission('galleries.editable') ? '<a href="javascript:void(0);" class="editable" title="' . __('cmsadmin::models/galleries.edit_caption') . '" data-title="' . __('cmsadmin::models/galleries.edit_caption') . '" data-field="caption" data-text="' . $model->caption . '" data-url="' . route('cmsadmin.galleries.editable', ['album' => $model->album_id, 'gallery' => $model->image_id]) . '">' . $model->caption . '</a>' : $model->caption;
            })
            ->editColumn('image_name', function ($gallery) {
                $html = renderFancyboxImage(ALBUM_FILE_DIR_NAME, $gallery->image_name, IMAGE_WIDTH_200);

                return checkCmsAdminPermissionList(['galleries.imageEdit', 'galleries.imageUpdate'])
                ? "<div class='d-flex'>$html<a title='" . __('common::crud.edit_image') . "' data-href='" . route('cmsadmin.galleries.imageEdit', ['id' => $gallery->image_id, 'field' => 'image_name']) . "' class='btn btn-sm text-primary imgUpdate'><i class='fas fa-pen'></i></a></div>"
                : "<div class='d-flex'>$html</div>";

            })
            ->editColumn('publish', function ($model) {
                return manageRenderBsSwitchGrid('galleries.togglePublish', 'publish', $model->image_id, $model->publish, 'cmsadmin.galleries.togglePublish', ['album' => $model->album_id]);
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->image_id . '">';
            })
            ->addColumn('action', 'cmsadmin::galleries.datatables_actions')
            ->rawColumns(['action', 'publish', 'selection', 'caption', 'show_order', 'image_name']);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Gallery $model)
    {
        $album_id = request()->route('album') | 0;

        return $model::with('album')->where('album_id', $album_id)->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $album_id = request()->route('album');

        $buttons = [];
        if (checkCmsAdminPermission('albums.index')) {
            $buttons[] = [
                'text' => __('cmsadmin::models/albums.singular'),
                'className' => 'btn btn-info btn-sm no-corner',
                'action' => "function (e, dt, button, config) { window.location = '" . route('cmsadmin.albums.index') . "';}",
            ];
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();
        $bulkActions = [];
        if (checkCmsAdminPermission('galleries.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.galleries.togglePublish', ['album' => $album_id]),
                'value' => 'toggle',
            ];
        }
        if (checkCmsAdminPermission('galleries.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.galleries.destroy', ['album' => $album_id, 'gallery' => 0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_GALLERY)
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
                    saveRowReorder(this.api().table(), '" . route('cmsadmin.galleries.reorder') . "');
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
            'action' => ['orderable' => false,  'title' => __('common::crud.action'), 'searchable' => false, 'class' => 'action-button-2-col'],
            [
                'title' => '',
                'data' => 'show_order',
                'class' => 'text-center align-middle drag-handle drag-col',
            ],
            [
                'data' => 'image_id',
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
            'caption' => new Column(['title' => __('cmsadmin::models/galleries.fields.caption'), 'data' => 'caption', 'class' => 'caption-col filter_text']),
            'image_name' => new Column(['title' => __('cmsadmin::models/galleries.fields.image_name'), 'data' => 'image_name', 'class' => 'image-col text-center']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'class' => 'status-col text-center filter_publish']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'galleries_datatable_' . time();
    }
}
