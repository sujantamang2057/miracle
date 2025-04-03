@push('page_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $(".remove-tmp-image").click(function(e) {
                e.preventDefault();
                window.swal.fire({
                    title: "{{ __('common::crud.messages.are_you_sure_to_remove_image') }}",
                    text: "{{ __('common::crud.messages.permanently_lost') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: "{{ __('common::crud.messages.yes_delete') }}",
                    cancelButtonText: "{{ __('common::crud.messages.no_keep_it') }}"
                }).then((result) => {
                    if (result.value) {
                        var url = $(this).attr("data-route");
                        var image_name = $(this).attr("data-name");
                        var currentEl = $(this);
                        $.ajax({
                            type: 'DELETE',
                            url: url,
                            data: image_name,
                            success: function(data) {
                                if (data === 'Ok') {
                                    currentEl.parent('.temp-image').remove();
                                }
                            }
                        });
                    } else if (result.dismiss === window.swal.DismissReason.cancel) {
                        // do nothing
                    }
                })
            });
        });
    </script>
@endpush
