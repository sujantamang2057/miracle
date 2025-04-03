@push('third_party_stylesheets')
    <link rel="stylesheet" href="{{ asset(THIRD_PARTY_ASSETS_DIR_PATH . '/slick/slick.min.css') }}" />
    <link rel="stylesheet" href="{{ asset(THIRD_PARTY_ASSETS_DIR_PATH . '/slick/slick-theme.min.css') }}" />
@endpush

@push('third_party_scripts')
    <script src="{{ asset(THIRD_PARTY_ASSETS_DIR_PATH . '/slick/slick.min.js') }}"></script>
@endpush
