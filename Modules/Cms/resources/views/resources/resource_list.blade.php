<section class="files-list mt-2 pb-5">
    <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
        @if ($resourceData && $resourceData->count())
            <div class="row row-gap-2 align-items-center">
                @foreach ($resourceData as $key => $data)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="file-box-wrapper d-flex align-items-center">
                            <i>{!! renderFileIcon($data->file_name) !!}</i>
                            <div class="flex-grow-1">
                                <div class="data fw-bold color-brown-800">
                                    {{ $data->display_name }}.<span>{{ $data->file_type }}</span>
                                </div>
                                <div class="para-text color-brown-700">
                                    {{ $data->file_size }}
                                </div>
                            </div>
                            <a href="{{ url('storage/' . RESOURCE_FILE_DIR_NAME . '/' . $data->file_name) }}"
                                download="{{ $data->display_name . '.' . $data->file_type }}" data-id="{{ $data->resource_id }}"
                                class="download-link d-inline-flex justify-content-center align-items-center para-text fw-bold text-decoration-none">
                                {{ __('cms::general.download') }}
                            </a>
                        </div>
                    </div>
                @endforeach
                @if ($resourceData->appends(request()->only(['year']))->total() > $resourceData->appends(request()->only(['year']))->perPage())
                    <nav aria-label="Pagination" class="resourcePagination pagination-wrapper col-xxl-10 px-xxl-0 mx-auto mb-3 px-5">
                        {{ $resourceData->appends(request()->only(['year']))->links() }}
                    </nav>
                @endif
            </div>
        @else
            <div class="not-found-class">
                <div class="fs-1 fw-bold color-theme text-center">
                    {{ __('common::messages.data_not_available') }}
                </div>
            </div>
        @endif
    </div>
</section>
@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('.download-link').click(function(e) {
                e.preventDefault();

                var fileId = $(this).data('id');
                var downloadUrl = $(this).attr('href');

                $.ajax({
                    url: '{{ route('cms.resources.downloadCount') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: fileId
                    },
                    success: function(response) {
                        if (response.success) {
                            var a = document.createElement('a');
                            a.href = downloadUrl;
                            a.setAttribute('download', '');
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                        } else {
                            window.swal.fire(response
                                .message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            });
        });
    </script>
@endpush
