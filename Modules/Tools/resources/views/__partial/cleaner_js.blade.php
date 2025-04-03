@include('common::__partial.swal-scripts')
@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            $('#clear_cache').on('click', function(event) {
                event.preventDefault();
                confirmCleaner(
                    event,
                    $(this).attr('href'),
                    "{{ __('tools::cleaners.clear_cache_confirm_msg') }}"
                );
            });

            $('#clear_config').on('click', function(event) {
                event.preventDefault();
                confirmCleaner(
                    event,
                    $(this).attr('href'),
                    "{{ __('tools::cleaners.clear_config_confirm_msg') }}"
                );
            });

            $('#clear_views').on('click', function(event) {
                event.preventDefault();
                confirmCleaner(
                    event,
                    $(this).attr('href'),
                    "{{ __('tools::cleaners.clear_views_confirm_msg') }}"
                );
            });

            $('#clear_route').on('click', function(event) {
                event.preventDefault();
                confirmCleaner(
                    event,
                    $(this).attr('href'),
                    "{{ __('tools::cleaners.clear_route_confirm_msg') }}"
                );
            });

            $('#clear_optimize').on('click', function(event) {
                event.preventDefault();
                confirmCleaner(
                    event,
                    $(this).attr('href'),
                    "{{ __('tools::cleaners.clear_optimize_confirm_msg') }}"
                );
            });

            $('#clear_permission_cache').on('click', function(event) {
                event.preventDefault();
                confirmCleaner(
                    event,
                    $(this).attr('href'),
                    "{{ __('tools::cleaners.clear_permission_cache_confirm_msg') }}"
                );
            });
        });

        function confirmCleaner(event, url, onText) {
            window.swal
                .fire({
                    title: onText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: "{{ __('common::general.yes') }}",
                    cancelButtonText: "{{ __('common::general.no') }}",
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        window.swal.fire({
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                window.swal.showLoading();
                            }
                        });
                        $.ajax({
                            type: 'GET',
                            url: url,
                            success: function() {
                                window.location.href = url;
                            },
                            error: function(xhr, status, error) {
                                window.swal.hideLoading();
                            }
                        });
                    }
                });
        }
    </script>
@endpush
