@push('page_scripts')
    <script type="text/javascript">
        function inputHtml(attr, index, maxlength, placeholder) {
            var inputHtml = '<div class = "col-sm-12">';
            inputHtml += '<input class = "form-control" maxlength="' + maxlength + '" name = "multidata[' + index +
                '][' + attr + ']" type="text" placeholder="' + placeholder + '">';
            inputHtml += '</div>';
            return inputHtml;
        }

        function radioHtml(attr, index, value, imageName, text) {
            var imgUrl = "{{ asset('img/multidata') }}" + '/' + imageName;
            var checkedHtml = value == 1 ? 'checked="checked"' : '';
            return '<input name="multidata[' + index + '][' + attr + ']" type="radio" value="' + value + '" id="detail-' +
                index + '-' + attr + '" ' + checkedHtml + '> <img width="100" src = "' + imgUrl + '" alt = "' + text +
                '" / > ';
        }

        function cloneHtml(index) {
            var marginClass = (index > 0) ? 'mt-4' : '';
            var html = '<div class="cloneBlock ' + marginClass + '"><div class="row required">';
            html += inputHtml('title', index, 255, '{{ __('common::multidata.fields.title') }}');
            html += '</div>';
            html += '<div class="row mt-2">';
            html += '<div class="col-sm-12">';
            html += '<input name="multidata[' + index + '][image_pre]" type="hidden">';
            html += '<input id="filepond' + index + '" class="my-pond" name="multidata[' + index + '][image]" type="file">';
            html += '</div></div>';
            html += '<div class="row mt-2">';
            html += '<div class="col-sm-12">';
            html += '<div><label for="layout">{{ __('common::multidata.fields.layout') }}</label></div>';
            html += '<input type="hidden" name="multidata[' + index + '][layout]" value="1">';
            html += radioHtml('layout', index, 1, 'image-left.png', 'Left');
            html += radioHtml('layout', index, 2, 'image-right.png', 'Right');
            html += radioHtml('layout', index, 3, 'image-top.png', 'Top');
            html += radioHtml('layout', index, 3, 'image-bottom.png', 'Bottom');
            html += '</div></div>';
            html += '<div class="row mt-2">';
            html += '<div class="col-sm-12">';
            html +=
                '<textarea class="form-control" id="tinymce_editor' + index + '" maxlength="65535" name="multidata[' +
                index +
                '][detail]" cols="50" rows="10" ></textarea></div></div>';
            html +=
                '<a href="javascript:void(0);" class="btn btn-danger btn-sm mb-2 mt-2 delete-multidata"><i class="fa fa-trash"></i> {{ __('common::crud.delete') }}</a></div>';
            return html;
        }

        $(function() {
            $('body').on('click', '.delete-multidata', function(e) {
                e.preventDefault();
                window.swal.fire({
                    title: '{{ __('common::multidata.delete_confirm_msg') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: "{{ __('common::crud.ok') }}",
                    cancelButtonText: "{{ __('common::crud.cancel') }}",
                }).then((result) => {
                    if (result.value) {
                        $(this).parent('.cloneBlock').remove();
                    }
                });
            });
            $('#addMore').on('click', function(e) {
                var length = $('.cloneBlock').length;
                if (length >= 10) {
                    return false;
                }
                $('#cloneParent').append(cloneHtml(length));
                initTinyMce('textarea#tinymce_editor' + length);
                initializeFilePond("filepond" + length, "image", moduleName, upload_url, delete_url,
                    isMultiUpload, true, length);
            });
        })
    </script>
@endpush
