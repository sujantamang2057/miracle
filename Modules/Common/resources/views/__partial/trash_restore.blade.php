@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            $('body').on('click', '.btn-trash-restore', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                window.swal
                    .fire({
                        title: "{{ __('common::crud.messages.trash_restore_confirmation') }}",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: "{{ __('common::crud.ok') }}",
                        cancelButtonText: "{{ __('common::crud.cancel') }}",
                    })
                    .then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: 'POST',
                                cache: false,
                                url: url,
                                success: function(data) {
                                    var backUrl = data.url,
                                        msg = data.msg;
                                    window.swal.fire(msg).then(function() {
                                        if (backUrl) {
                                            window.location = backUrl;
                                        }
                                    });
                                },
                            });
                        }
                    });
            });
        });
    </script>
@endpush
