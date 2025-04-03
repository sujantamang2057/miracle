@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            $('body').on('click', '.editable', function(e) {
                e.preventDefault();
                var currentEl = $(this);
                var title = currentEl.attr('data-title');
                var field = currentEl.attr('data-field');
                var text = currentEl.attr('data-text');
                var url = currentEl.attr('data-url');
                window.swal.fire({
                    title: title,
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off',
                    },
                    inputValidator: (value) => {
                        return !value && '{{ __('common::crud.messages.field_required') }}'
                    },
                    inputValue: text,
                    showCancelButton: true,
                    confirmButtonText: '{{ __('common::crud.btn.update') }}',
                    cancelButtonText: '{{ __('common::crud.cancel') }}',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            data: {
                                field: field,
                                value: result.value,
                            },
                            type: 'PATCH',
                            cache: false,
                            url: url,
                            success: function(data) {
                                reloadDatatable();
                                showFlashAlert(data);
                            },
                        });
                    }
                });
            });
        })
    </script>
@endpush
