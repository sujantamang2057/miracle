@push('page_scripts')
    <script>
        function initializeSelect2(selector, data, tags, limit) {
            data = data || null;
            tags = tags || false;
            limit = limit || 10;
            if (selector) {
                $(selector).select2({
                    data: data,
                    tags: tags,
                    theme: 'bootstrap4',
                    tokenSeparators: [',', ' '],
                    maximumSelectionLength: limit
                });
            }
        }
    </script>
@endpush
