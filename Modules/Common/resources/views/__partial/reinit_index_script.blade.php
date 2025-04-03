@push('page_scripts')
    <script type="text/javascript">
        $(document).on({
            ajaxStop: function() {
                if ($('.active_toggle').length) {
                    $('.active_toggle').bootstrapSwitch({
                        'onText': "{{ __('common::general.active_text_bs') }}",
                        'offText': "{{ __('common::general.inactive_text_bs') }}",
                        "onColor": "success",
                        "offColor": "danger",
                    });
                }
                if ($('.publish_toggle').length) {
                    $('.publish_toggle').bootstrapSwitch({
                        'onText': "{{ __('common::general.publish_text_bs') }}",
                        'offText': "{{ __('common::general.unpublish_text_bs') }}",
                        "onColor": "success",
                        "offColor": "danger",
                    });
                }
                if ($('.reserved_toggle').length) {
                    $('.reserved_toggle').bootstrapSwitch({
                        'onText': "{{ __('common::general.reserved_text_bs') }}",
                        'offText': "{{ __('common::general.nonreserved_text_bs') }}",
                        "onColor": "success",
                        "offColor": "danger",
                    });
                }
            }
        });
    </script>
@endpush
