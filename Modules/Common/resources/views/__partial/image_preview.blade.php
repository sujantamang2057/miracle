@push('page_scripts')
    <script type="text/javascript">
        function loadPreview(input, id) {
            id = id || '#preview_img';
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(id)
                        .attr('src', e.target.result)
                        .width(200)
                        .height(150)
                        .show();
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
@push('page_css')
    <style>
        .img_preview {
            display: none;
            margin: 2px;
        }
    </style>
@endpush
