@push('page_scripts')
    <script type="text/javascript">
        function reinitizeBsSwitch(selector, onText, offText) {
            onText = typeof onText != 'undefined' ? onText : "{{ __('common::general.yes') }}";
            offText = typeof offText != 'undefined' ? offText : "{{ __('common::general.no') }}";
            if ($(selector).length) {
                $(selector).bootstrapSwitch({
                    onText: onText,
                    offText: offText,
                    onColor: 'success',
                    offColor: 'danger',
                });
            }
        }

        function reloadDatatable(callback) {
            $('#dataTableBuilder')
                .DataTable()
                .ajax.reload(function() {
                    $('#bulkActions').val(0);
                    $('#select-all').prop('checked', false);
                    reinitizeBsSwitch('.active_toggle', "{{ __('common::general.active_text_bs') }}", "{{ __('common::general.inactive_text_bs') }}");
                    reinitizeBsSwitch('.publish_toggle', "{{ __('common::general.publish_text_bs') }}",
                        "{{ __('common::general.unpublish_text_bs') }}");
                    reinitizeBsSwitch('.reserved_toggle', "{{ __('common::general.reserved_text_bs') }}",
                        "{{ __('common::general.nonreserved_text_bs') }}");

                    if (typeof callback === "function") {
                        callback();
                    }
                }, false);
        }

        function showFlashAlert(data) {
            if (data && data.msg) {
                var type = data.msgType || '';
                var alertHtml = '<div class="alert alert-' + type + '" role="alert">' + data.msg + '</div>';
                $('.content').find('.alert').remove();
                $('.content').prepend(alertHtml);
            }
        }

        $(function() {
            $('#select-all').parent('th').attr('title', '{{ __('common::crud.select_unselect_all') }}');
            LaravelDataTables.dataTableBuilder.on('buttons-action', function(e, buttonApi, dataTable, node,
                config) {
                if (node.hasClass('buttons-reset') || node.hasClass('buttons-reload')) {
                    $('#select-all').prop('checked', false);
                    $('#bulkActions').val(0);
                }
            });
            $('body').on('click', '.btn-apply-bulk', function(e) {
                e.preventDefault();
                var bulkActionEl = $('#bulkActions');
                var bulkAction = bulkActionEl.val(),
                    url = bulkActionEl.find('option:checked').attr('data-url'),
                    selection = [],
                    checkIdList = 0;

                $('input[class=select-data]:checked').each(function(i) {
                    selection[i] = $(this).val();
                });

                if (selection.length > 0) {
                    checkIdList = 1;
                }
                var data = {
                    id: selection,
                };
                if (bulkAction && url) {
                    if (selection.length > 0) {
                        if (bulkAction == 'toggle') {
                            window.swal
                                .fire({
                                    title: "{{ __('common::crud.messages.bulk_toggle_publish_confirmation') }}",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: "{{ __('common::crud.ok') }}",
                                    cancelButtonText: "{{ __('common::crud.cancel') }}",
                                })
                                .then((result) => {
                                    if (result.value) {
                                        $.ajax({
                                            data: data,
                                            type: 'POST',
                                            cache: false,
                                            url: url,
                                            success: function(data) {
                                                reloadDatatable(function() {
                                                    showFlashAlert(
                                                        data);
                                                });
                                            },
                                        });
                                    }
                                });
                        } else if (bulkAction == 'toggle-active') {
                            window.swal
                                .fire({
                                    title: "{{ __('common::crud.messages.bulk_toggle_active_confirmation') }}",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: "{{ __('common::crud.ok') }}",
                                    cancelButtonText: "{{ __('common::crud.cancel') }}",
                                })
                                .then((result) => {
                                    if (result.value) {
                                        $.ajax({
                                            data: data,
                                            type: 'POST',
                                            cache: false,
                                            url: url,
                                            success: function(data) {
                                                reloadDatatable(function() {
                                                    showFlashAlert(
                                                        data);
                                                });
                                            },
                                        });
                                    }
                                });
                        } else if (bulkAction == 'delete') {
                            window.swal
                                .fire({
                                    title: "{{ __('common::crud.messages.bulk_delete_confirmation') }}",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: "{{ __('common::crud.ok') }}",
                                    cancelButtonText: "{{ __('common::crud.cancel') }}",
                                })
                                .then((result) => {
                                    if (result.value) {
                                        $.ajax({
                                            data: data,
                                            type: 'DELETE',
                                            cache: false,
                                            url: url,
                                            success: function(data) {
                                                reloadDatatable(function() {
                                                    showFlashAlert(
                                                        data);
                                                });
                                            },
                                        });
                                    }
                                });
                        } else if (bulkAction == 'restore') {
                            window.swal
                                .fire({
                                    title: "{{ __('common::crud.messages.bulk_restore_confirmation') }}",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: "{{ __('common::crud.ok') }}",
                                    cancelButtonText: "{{ __('common::crud.cancel') }}",
                                })
                                .then((result) => {
                                    if (result.value) {
                                        $.ajax({
                                            data: data,
                                            type: 'POST',
                                            cache: false,
                                            url: url,
                                            success: function(data) {
                                                reloadDatatable(function() {
                                                    showFlashAlert(
                                                        data);
                                                });
                                            },
                                        });
                                    }
                                });
                        } else if (bulkAction == 'regenerate') {
                            window.swal
                                .fire({
                                    title: "{{ __('common::crud.messages.bulk_regenerate_confirmation') }}",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: "{{ __('common::crud.ok') }}",
                                    cancelButtonText: "{{ __('common::crud.cancel') }}",
                                })
                                .then((result) => {
                                    if (result.value) {
                                        $.ajax({
                                            data: data,
                                            type: 'POST',
                                            cache: false,
                                            url: url,
                                            success: function(data) {
                                                reloadDatatable(function() {
                                                    showFlashAlert(
                                                        data);
                                                });
                                            },
                                        });
                                    }
                                });
                        } else {
                            window.swal.fire("{{ __('common::crud.messages.select_bulk_action') }}");
                        }
                    } else {
                        window.swal.fire("{{ __('common::crud.messages.select_items') }}");
                    }
                } else {
                    window.swal.fire("{{ __('common::crud.messages.select_bulk_action') }}");
                }
            });
            $('body').on('click', '#select-all', function(e) {
                if ($(this).prop('checked')) {
                    $('input.select-data:not(:checked)').prop('checked', true);
                } else {
                    $('input.select-data:checked').prop('checked', false);
                }
            });
        });
    </script>
@endpush
