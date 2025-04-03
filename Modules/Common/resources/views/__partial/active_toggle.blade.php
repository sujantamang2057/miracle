@include('common::__partial.bs_switch_script')
@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            var selector = ".active_toggle",
                onText = "{{ __('common::general.active_text_bs') }}",
                offText = "{{ __('common::general.inactive_text_bs') }}",
                confirmMsg = "{{ __('common::crud.messages.toggle_active_confirmation') }}",
                errorMsg = "{{ __('common::general.active_toggle_failed') }}";

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
