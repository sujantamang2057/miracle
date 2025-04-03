@push('third_party_stylesheets')
    <link rel="stylesheet" href="{{ asset(THIRD_PARTY_ASSETS_DIR_PATH . '/glightbox/glightbox.min.css') }}" />
@endpush

@push('third_party_scripts')
    <script src="{{ asset(THIRD_PARTY_ASSETS_DIR_PATH . '/glightbox/glightbox.min.js') }}"></script>
@endpush

@push('page_scripts')
    <script type="text/javascript">
        const lightbox = GLightbox({
            touchNavigation: true,
            autoplayVideos: true,
            closeOnOutsideClick: true,
        });
    </script>
@endpush
