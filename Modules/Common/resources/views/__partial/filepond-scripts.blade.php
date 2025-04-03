@push('page_css')
    <style>
        .filepond--credits {
            display: none;
        }
    </style>
@endpush
@push('page_scripts')
    <script>
        function initializeFilePond(elementId, elementName, moduleName, upload_url, delete_url, isMultiUpload, multidata,
            index, maxImageSize, minImageSize) {

            // lang
            var lang = "{{ getAppLocaleEx() }}";
            if ("ja" == lang) {
                FilePond.setOptions(FilePond_lang_ja_JA);
            }

            FilePond.registerPlugin(
                FilePondPluginImagePreview,
                FilePondPluginFileValidateType,
                FilePondPluginFileValidateSize,
                FilePondPluginImageValidateSize,
                FilePondPluginImageExifOrientation,
            );
            minImageSize = minImageSize || '{{ MIN_IMAGE_UPLOAD_SIZE }}';
            maxImageSize = maxImageSize || '{{ MAX_IMAGE_UPLOAD_SIZE }}';

            const inputElementById = document.querySelector('input[id="' + elementId + '"]');

            // Create a FilePond instance
            const pond = FilePond.create(inputElementById, {
                // FilePondPluginImagePreview
                allowImagePreview: true,
                imagePreviewMaxHeight: 200,
                // FilePondPluginFileValidateType
                allowFileTypeValidation: true,
                acceptedFileTypes: @json(ALLOWED_IMAGE_TYPES),
                // FilePondPluginFileValidateSize
                allowFileSizeValidation: true,
                minFileSize: minImageSize,
                maxFileSize: maxImageSize,
                // FilePondPluginImageValidateSize
                allowImageValidateSize: true,
                imageValidateSizeMinWidth: 100,
                imageValidateSizeMinHeight: 100,
                imageValidateSizeMaxWidth: 9000,
                imageValidateSizeMaxHeight: 9000,
                // FilePondPluginImageExifOrientation
                allowImageExifOrientation: true,
                // general settings
                allowProcess: false,
                allowMultiple: isMultiUpload,
                name: 'filepond',
                server: {
                    process: {
                        url: upload_url,
                        method: 'POST',
                        withCredentials: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        timeout: 7000,
                        onload: null,
                        onerror: null,
                        ondata: (formData) => {
                            formData.append('module_name', moduleName);
                            formData.append('file_element_name', elementName);
                            if (multidata) {
                                formData.append('multidata', true);
                                formData.append('index', index);
                            }
                            const inputEl = document.getElementById(elementId + '-' + elementName);
                            if (inputEl) {
                                inputEl.remove();
                            }
                            return formData;
                        }
                    },
                    revert: {
                        url: delete_url,
                        method: 'delete',
                        withCredentials: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        timeout: 7000,
                        onload: null,
                        onerror: null,
                        ondata: null,
                    },
                },
            });
        }
    </script>
@endpush
