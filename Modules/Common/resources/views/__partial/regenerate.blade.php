@push('page_scripts')
    <script type="text/javascript">
        var switchConfig = (switchConfig) ? switchConfig : {
            'onText': "Yes",
            'offText': "No",
            "onColor": "success",
            "offColor": "danger",
        };
        $('body').on('click', '.btn-regenerate', function(e) {
            e.preventDefault();
            var url = $(this).attr('href'),
                id = $(this).attr('data-id');
            if (url && id) {
                $.ajax({
                    data: {
                        id: id
                    },
                    type: 'POST',
                    cache: false,
                    url: url,
                    success: function(data) {
                        if ($("#dataTableBuilder").length) {
                            $("#dataTableBuilder").DataTable().ajax.reload(function() {
                                $('#bulkActions').val(0);
                                $('#select-all').prop('checked', false);
                                if ($('.publish_toggle').length) {
                                    $('.publish_toggle').bootstrapSwitch(switchConfig);
                                }
                                if ($('.reserved_toggle').length) {
                                    $('.reserved_toggle').bootstrapSwitch(switchConfig);
                                }
                                showFlashAlert(data);
                            }, false);
                        } else {
                            showFlashAlert(data);
                        }
                    }
                });
            }
        });
    </script>
@endpush
