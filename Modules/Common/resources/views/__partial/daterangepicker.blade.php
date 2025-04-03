@push('page_scripts')
    <script>
        var json_date = @json(getSingleDateTimerPickerConfig(false)),
            json_time = @json(getSingleDateTimerPickerConfig(true)),
            format_date = '{{ DATE_RANGE_PICKER_DATE_FORMAT }}',
            format_time = '{{ DATE_RANGE_PICKER_DATETIME_FORMAT }}';

        function getFormat(picker) {
            return (picker.timePicker || false) ? format_time : format_date;
        }

        function initializeDateRangePicker(elementId, showTimePicker) {
            showTimePicker = showTimePicker || false;
            json_config_ = (showTimePicker == true) ? json_time : json_date;
            $(elementId).daterangepicker(json_config_)
                .on('show.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format(getFormat(picker)));
                })
                .on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format(getFormat(picker)));
                });
        }
    </script>
@endpush
