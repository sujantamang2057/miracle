<div class="card-body px-4">
    {!! $dataTable->table(generateDataTableConfig()) !!}
</div>

@push('third_party_scripts')
    @include('common::__partial.buttons_datatables')
    {!! $dataTable->scripts() !!}
@endpush

@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            $('body').on('click', '.set-cover-image', function(e) {
                e.preventDefault();
                var albumId = $(this).attr('data-album_id');
                var imageId = $(this).attr('data-id');
                window.swal.fire({
                    title: '{{ __('cmsadmin::models/albums.messages.set_cover_image_msg') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('common::general.yes') }}',
                    cancelButtonText: '{{ __('common::general.no') }}'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'PATCH',
                            url: '{{ url('cmsadmin/album') }}' + '/' + albumId + '/set-cover-image',
                            data: {
                                gallery: imageId
                            },
                            success: function(data) {
                                if (data) {
                                    reloadDatatable();
                                    showFlashAlert(data);
                                } else {
                                    location.href =
                                        "{{ route('cmsadmin.albums.index') }}";
                                }
                            }
                        });
                    }
                })
            });
        })
    </script>
@endpush
