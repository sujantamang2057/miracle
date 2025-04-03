@push('page_scripts')
    <script>
        function submitPreview(formId, routeUrl) {
            event.preventDefault();
            $('#' + formId + ' .text-danger').remove();
            $('#' + formId + '.has-error').removeClass('has-error');
            $('#' + formId + '.is-invalid').removeClass('is-invalid');
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
            const originalForm = document.getElementById(formId);
            const clone = originalForm.cloneNode(true);
            if (clone) {
                clone.querySelectorAll('select').forEach((select, index) => {
                    select.value = originalForm.querySelectorAll('select')[index].value;
                });
                const formData = new FormData(originalForm);
                formData.delete('_method');
                $.ajax({
                    url: routeUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            clone.removeAttribute('id');
                            clone.setAttribute('target', '_blank');
                            clone.action = routeUrl;
                            const methodInput = clone.querySelector(
                                'input[name="_method"]'
                            );
                            if (methodInput) {
                                methodInput.remove();
                            }
                            document.body.appendChild(clone);
                            clone.submit();
                            setTimeout(function() {
                                if (clone.parentNode) {
                                    clone.parentNode.removeChild(clone);
                                }
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var responseJson = xhr.responseJSON;
                            if (responseJson && responseJson.errors) {
                                $.each(responseJson.errors, function(key, value) {
                                    var inputField = $('#' + formId + ' #' + key);
                                    if (tinymce.get(key)) {
                                        var editorContainer = tinymce.get(key).editorContainer;
                                        $(editorContainer).parent().addClass('has-error error');
                                        $(editorContainer).next('.text-danger').remove();
                                        $(editorContainer).after('<p class="text-danger">' + value[0] + '</p>');
                                    } else {
                                        inputField.parent().addClass('has-error error');
                                        var errorElement = inputField.next('.text-danger');
                                        if (errorElement.length) {
                                            errorElement.text(value[0]);
                                        } else {
                                            inputField.after('<p class="text-danger">' + value[0] + '</p>');
                                        }
                                    }
                                });
                            }
                        }
                    }

                });
            }
        }
    </script>
@endpush
