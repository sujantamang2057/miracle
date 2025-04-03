@push('page_scripts')
    <script type="text/javascript">
        function showFlashAlert(data) {
            if (data && data.msg) {
                var type = data.msgType || '';
                var alertHtml = '<div class="alert alert-' + type + '" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span>' +
                    '</button>' +
                    data.msg +
                    '</div>';
                $('.content').find('.alert').remove();
                $('.content').prepend(alertHtml);
            }
        }
    </script>
@endpush
