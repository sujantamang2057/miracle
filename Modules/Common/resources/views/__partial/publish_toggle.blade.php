@include('common::__partial.bs_switch_script')
@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            var selector = ".publish_toggle",
                onText = "{{ __('common::general.publish_text_bs') }}",
                offText = "{{ __('common::general.unpublish_text_bs') }}",
                confirmMsg = "{{ __('common::crud.messages.toggle_publish_confirmation') }}",
                errorMsg = "{{ __('common::general.publish_toggle_failed') }}";

            $(selector).bootstrapSwitch({
                'onText': onText,
                'offText': offText,
                "onColor": "success",
                "offColor": "danger",
            });

            toggleData(selector, onText, offText, confirmMsg, errorMsg);
        })
    </script>
@endpush
