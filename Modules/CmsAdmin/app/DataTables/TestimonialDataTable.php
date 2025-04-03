<?php

namespace Modules\CmsAdmin\app\DataTables;

use Modules\CmsAdmin\app\Models\Testimonial;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TestimonialDataTable extends DataTable
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
            ->setRowId('testimonial_id')
            ->setRowAttr([
                'data-index' => function ($testimonial) {
                    return $testimonial->testimonial_id;
                },
                'data-position' => function ($testimonial) {
                    return $testimonial->show_order;
                },
            ])
            ->editColumn('tm_name', function ($model) {
                return checkCmsAdminPermissionList(['testimonials.edit', 'testimonials.update']) ? '<a href="' . route('cmsadmin.testimonials.edit', $model->testimonial_id) . '">' . $model->tm_name . '</a>' : $model->tm_name;
            })
            ->editColumn('show_order', function ($banner) {
                return "<i class='fas fa-arrows-alt text-green sortable'></i>";
            })
            ->editColumn('tm_profile_image', function ($model) {
                $html = renderFancyboxImage(TESTIMONIAL_FILE_DIR_NAME, $model->tm_profile_image, IMAGE_WIDTH_200);

                return checkCmsAdminPermissionList(['testimonials.imageEdit', 'testimonials.imageUpdate'])
                ? "<div class='d-flex'>$html<a title='" . __('common::crud.edit_image') . "' data-href='" . route('cmsadmin.testimonials.imageEdit', ['id' => $model->testimonial_id, 'field' => 'tm_profile_image']) . "' class='btn btn-sm text-primary imgUpdate'><i class='fas fa-pen'></i></a></div>"
                : "<div class='d-flex'>$html</div>";
            })
            ->addColumn('selection', function ($model) {
                return '<input type="checkbox" name="selections[]" class="select-data" value="' . $model->testimonial_id . '">';
            })
            ->addColumn('action', 'cmsadmin::testimonials.datatables_actions')
            ->editColumn('publish', function ($model) {
                return manageRenderBsSwitchGrid('testimonials.togglePublish', 'publish', $model->testimonial_id, $model->publish, 'cmsadmin.testimonials.togglePublish');
            })
            ->rawColumns(['action', 'selection', 'show_order', 'tm_name', 'tm_profile_image', 'publish']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \app\Models\Testimonial  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Testimonial $model)
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

        if (checkCmsAdminPermissionList(['testimonials.create', 'testimonials.store'])) {
            $buttons[] = getCreateButton();
        }
        $buttons[] = getResetButton();
        $buttons[] = getReloadButton();

        if (checkCmsAdminPermission('testimonials.trashList')) {
            $buttons[] = getTrashButton(route('cmsadmin.testimonials.trashList'));
        }
        $bulkActions = [];
        if (checkCmsAdminPermission('testimonials.togglePublish')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_publish_toggle_option'),
                'url' => route('cmsadmin.testimonials.togglePublish'),
                'value' => 'toggle',
            ];
        }
        if (checkCmsAdminPermission('testimonials.destroy')) {
            $bulkActions[] = [
                'label' => __('common::crud.text.bulk_delete_option'),
                'url' => route('cmsadmin.testimonials.destroy', [0]),
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
            ->pageLength(DEFAULT_PAGE_SIZE_TESTIMONIAL)
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
                    saveRowReorder(this.api().table(), '" . route('cmsadmin.testimonials.reorder') . "');
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
                'data' => 'testimonial_id',
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
            'tm_name' => new Column(['title' => __('cmsadmin::models/testimonials.fields.tm_name'), 'data' => 'tm_name', 'class' => 'filter_text']),
            'tm_email' => new Column(['title' => __('cmsadmin::models/testimonials.fields.tm_email'), 'data' => 'tm_email', 'class' => 'filter_text']),
            'tm_profile_image' => new Column(['title' => __('cmsadmin::models/testimonials.fields.tm_profile_image'), 'data' => 'tm_profile_image', 'class' => 'category-image-col']),
            'publish' => new Column(['title' => __('common::crud.fields.publish'), 'data' => 'publish', 'class' => 'status-col filter_publish']),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'testimonials_datatable_' . time();
    }
}
