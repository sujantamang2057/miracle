@include('common::__partial.swal-scripts')
@pushOnce('page_scripts')
    <script>
        function resetResponse(e, btnSelector) {
            $(btnSelector).attr({
                offset: 0,
                disabled: false
            });
            $(btnSelector).removeAttr('regenerated');
            $('.response').html('');
        }
    </script>
@endPushOnce
