@extends('tools::layouts.master')

@section('content')
    {{ Breadcrumbs::render('backup_cleaner') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2"></div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-6">
            <div class="card-header">
                <h4>{{ __('tools::backup_cleaners.title') }}</h4>
            </div>

            <div class="card-body px-0">
                <div class="d-flex">
                    @if (checkToolsPermission('backupCleaner.cleanBlocks'))
                        <a id= "clear_block_backup" class="btn btn-danger btn-sm no-corner mr-2"
                            href="{!! route('tools.backupCleaner.cleanBlocks') !!}">{{ __('tools::backup_cleaners.btn.clear_block_backup') }}</a>
                    @endif
                    @if (checkToolsPermission('backupCleaner.cleanPages'))
                        <a id= "clear_page_backup" class="btn btn-danger btn-sm no-corner mr-2"
                            href="{!! route('tools.backupCleaner.cleanPages') !!}">{{ __('tools::backup_cleaners.btn.clear_page_backup') }}</a>
                    @endif
                    @if (checkToolsPermission('backupCleaner.cleanEmailTemplates'))
                        <a id= "clear_template_backup" class="btn btn-danger btn-sm no-corner mr-2"
                            href="{!! route('tools.backupCleaner.cleanEmailTemplates') !!}">{{ __('tools::backup_cleaners.btn.clear_template_backup') }}</a>
                    @endif
                </div>

                <div class="row">
                    <div id="htmlContent" class="col-sm-12">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page_scripts')
    <script>
        $('#clear_block_backup').on('click', function(event) {
            event.preventDefault();
            confirmCleaner(
                event,
                $(this).attr('href'),
                "{{ __('tools::backup_cleaners.message.clear_block_backup') }}"
            );
        });
        $('#clear_page_backup').on('click', function(event) {
            event.preventDefault();
            confirmCleaner(
                event,
                $(this).attr('href'),
                "{{ __('tools::backup_cleaners.message.clear_page_backup') }}"
            );
        });
        $('#clear_template_backup').on('click', function(event) {
            event.preventDefault();
            confirmCleaner(
                event,
                $(this).attr('href'),
                "{{ __('tools::backup_cleaners.message.clear_email_template_backup') }}"
            );
        });

        function confirmCleaner(event, url, onText) {
            window.swal
                .fire({
                    title: onText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: "{{ __('common::general.yes') }}",
                    cancelButtonText: "{{ __('common::general.no') }}",
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        window.swal.fire({
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                window.swal.showLoading();
                            }
                        });
                        $.ajax({
                            type: 'POST',
                            url: url,
                            success: function(response) {
                                $('#htmlContent').html(response.html);
                                window.swal.close();
                            },
                            error: function(xhr, status, error) {
                                window.swal.close();
                            }
                        });
                    }
                });
        }
    </script>
@endpush
