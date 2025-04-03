@include('common::__partial.show_alert')

@pushOnce('page_scripts')
    <script type="text/javascript">
        function toggleData(selector, onText, offText, confirmMsg, errorMsg) {
            var switchConfig = {
                'onText': onText,
                'offText': offText,
                "onColor": "success",
                "offColor": "danger",
            };

            $(document.body).delegate(selector, 'switchChange.bootstrapSwitch', function(e, state) {
                // prevent toggle for confirmation
                $(e.currentTarget).bootstrapSwitch('state', !state, true);
                var url = $(this).attr('data-route');
                var id = $(this).attr('data-id');
                window.swal.fire({
                    title: confirmMsg,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: "{{ __('common::crud.ok') }}",
                    cancelButtonText: "{{ __('common::crud.cancel') }}",
                }).then((result) => {
                    if (result.value) {
                        if (url && id) {
                            $.ajax({
                                data: {
                                    id: id
                                },
                                type: 'POST',
                                cache: false,
                                url: url,
                                success: function(data) {
                                    $(e.currentTarget).bootstrapSwitch('state', state, true);
                                    if ($("#dataTableBuilder").length) {
                                        $("#dataTableBuilder").DataTable().ajax.reload(function() {
                                            $(selector).bootstrapSwitch(switchConfig);
                                            showFlashAlert(
                                                data);
                                        }, false);
                                    } else {
                                        showFlashAlert(data);
                                    }
                                }
                            });
                        } else {
                            window.swal.fire(errorMsg);
                        }
                    }
                });
            });
        }
    </script>
@endPushOnce
