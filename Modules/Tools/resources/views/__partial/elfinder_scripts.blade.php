@push('page_scripts')
    <!-- elFinder initialization (REQUIRED) -->
    <script type="text/javascript" charset="utf-8">
        // Documentation for client options:
        // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
        $().ready(function() {
            $('#elfinder').elfinder({
                // set your elFinder options here
                @if ($locale)
                    lang: '{{ $locale }}', // locale
                @endif
                customData: {
                    _token: '{{ csrf_token() }}'
                },
                url: '{{ route('elfinder.connector') }}', // connector URL
                soundPath: '{{ asset($dir . '/sounds') }}'
            });

            var elfinderInstance = $('#elfinder').elfinder({
                width: '100%'
            }).elfinder('instance');

            resizeElFinder();

            // fit to window.height on window.resize
            var resizeTimer = null;
            $(window).resize(function() {
                resizeTimer && clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    resizeElFinder();
                }, 200);
            });

            function resizeElFinder() {
                var h = parseInt($(window).height()) - 250;
                if (h != parseInt($('#elfinder').height())) {
                    elfinderInstance.resize('100%', h);
                }
            }

        });
    </script>
@endpush
