@push('page_scripts')
    <script type="text/javascript">
        function confirmDelete(event, formId, type = null) {
            event.preventDefault();
            let confirmationText = type ?
                "{{ __('common::crud.messages.will_not_able_to_recover_data') }}" :
                "{{ __('common::crud.messages.will_move_to_trash') }}";

            window.swal.fire({
                title: "{{ __('common::crud.messages.are_you_sure_to_delete') }}",
                text: confirmationText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "{{ __('common::crud.messages.yes_delete') }}",
                cancelButtonText: "{{ __('common::crud.messages.no_keep_it') }}"
            }).then((result) => {
                if (result.value) {
                    if (formId != undefined) {
                        document.getElementById(formId).submit();
                    }
                }
            })
        }
    </script>
@endpush
