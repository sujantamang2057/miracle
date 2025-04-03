@include('common::__partial.bs_switch_script')
@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            var selector = ".reserved_toggle",
                onText = "{{ __('common::general.reserved_text_bs') }}",
                offText = "{{ __('common::general.nonreserved_text_bs') }}",
                confirmMsg = "{{ __('common::crud.messages.toggle_reserved_confirmation') }}",
                errorMsg = "{{ __('common::general.reserved_toggle_failed') }}";

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
