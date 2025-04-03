@extends('layouts.app')

@section('content')
{{ Breadcrumbs::render('CspHeader') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('cmsadmin::models/csp_headers.singular') }}</h1>
                </div>
                <div class="col-sm-6">

                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            @include('cmsadmin::csp_headers.table')
        </div>
    </div>

    <div class="modal fade" id="cspHeaderEdit" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div id="cspHeaderEditContent"></div>
            </div>
        </div>
    </div>

    @pushOnce('page_scripts')
        <script type="text/javascript">
            $('body').on('click', '.cspHeaderEdit', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var currentSwal = window.swal;
                currentSwal.showLoading();
                $('#cspHeaderEditContent').load(url, function(response, status, xhr) {
                    if (status == "error") {
                        window.swal.fire(
                            '{{ __('cmsadmin::models/csp_headers.messages.failed_to_load_form') }}');
                    } else {
                        currentSwal.close();
                        if (status == "success") {
                            $('#cspHeaderEditContent').find('#approveApplicantForm script').each(function() {
                                $.globalEval(this.text || this.textContent || this.innerHTML || '');
                            });

                            $('#cspHeaderEdit').modal('show');
                        }
                    }
                });
            });
            $('#cspHeaderEdit').on('hidden.bs.modal', function() {
                $('#approveApplicantForm').trigger('reset');
                $('.has-error').removeClass('has-error');
                $('.text-danger.error').text('');
            });

            $('body').on('submit', '#approveApplicantForm', function(event) {
                event.preventDefault();
                $("#submitApprovalBtn").attr("disabled", true);
                $('#approveApplicantForm .text-danger').remove();
                var form = $(this);
                const originalForm = document.getElementById('approveApplicantForm');
                var formData = new FormData(originalForm);
                $.ajax({
                    type: 'POST',
                    url: form.attr('action'),
                    data: formData,
                    enctype: 'multipart/form-data',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#submitApprovalBtn").attr("disabled", false);
                        originalForm.submit();
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            $("#submitApprovalBtn").attr("disabled", false);
                            var responseJson = xhr.responseJSON;
                            if (responseJson && responseJson.errors) {
                                $.each(responseJson.errors, function(key, value) {
                                    var inputField = $('#approveApplicantForm [name="' + key.split('.')[0] + '[]"]');
                                    if (inputField.hasClass('select2-hidden-accessible')) {
                                        var select2Container = inputField.next('.select2-container');
                                        select2Container.parent().addClass('has-error error');
                                        select2Container.next('.text-danger').remove();
                                        select2Container.after('<p class="text-danger">' + value[0] + '</p>');
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
            });
        </script>
    @endpushOnce
@endsection

@include('common::__partial.show_alert')
@include('common::__partial.select2-scripts')
@include('common::__partial.reinit_index_script')
@include('common::__partial.publish_toggle')
@include('common::__partial.datatables-column-filter')
