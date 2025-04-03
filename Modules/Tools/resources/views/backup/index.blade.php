@extends('tools::layouts.master')

@section('content')
    {{ Breadcrumbs::render('db_backup') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">@include('flash::message')</div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @include('flash::message')

        <div class="card mb-6">
            <div class="card-header">
                <h4>{{ __('tools::common.db_backup') }}</h4>
            </div>

            <div class="card-body px-0">
                <div class="d-flex">
                    @if (checkToolsPermissionList(['backup.edit', 'backup.update']))
                        <a class="btn btn-success btn-sm no-corner mr-2" href="{!! route('tools.backup.create') !!}">{{ __('tools::backups.create_a_new_backup') }}</a>
                    @endif
                    <a class="btn btn-warning btn-sm no-corner mr-2" href="{!! route('tools.backup.index') !!}"
                        id="refresh-button">{{ __('tools::backups.refresh') }}</a>
                    @if (checkToolsPermission('backup.bulkDelete'))
                        <a class="btn btn-danger btn-sm no-corner mr-2" id="bulk-delete-button" href="javascript:void(0);"
                            data-method="delete">{{ __('tools::backups.delete_all') }}</a>
                    @endif
                </div>
                <div class="row alert-msg">
                    @include('tools::backup.table')
                </div>
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.swal_datatable')
@include('common::__partial.swal-scripts')
@push('page_scripts')
    <script>
        document.getElementById('bulk-delete-button').addEventListener('click', function() {
            var fileNames = [];
            var diskName = "{{ !empty($backups) && isset($backups[0]) ? $backups[0]->diskName : '' }}";

            @if (!empty($backups))
                @foreach ($backups as $backup)
                    fileNames.push('{{ $backup->fileNameWithPath }}');
                @endforeach
            @endif

            if (fileNames.length > 0) {
                window.swal
                    .fire({
                        title: "{{ __('common::crud.messages.bulk_delete_confirmation') }}",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: "{{ __('common::crud.ok') }}",
                        cancelButtonText: "{{ __('common::crud.cancel') }}",
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
                                url: "{{ route('tools.backup.bulkDelete') }}",
                                type: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: "{{ csrf_token() }}",
                                    disk: diskName,
                                    file_names: JSON.stringify(fileNames)
                                },
                                success: function(response) {
                                    location.reload();
                                },
                                error: function(xhr) {
                                    window.swal.fire(
                                        "{{ __('common::crud.messages.error_occurred') }}", xhr
                                        .responseText, 'error');
                                }
                            });
                        }
                    });
            } else {
                window.swal.fire("{{ __('common::crud.messages.empty_backup_file') }}");
                return;
            }
        });
        document.querySelector('a[href="{!! route('tools.backup.create') !!}"]').addEventListener('click', function(event) {
            event.preventDefault();
            showSwalLoading();
            window.location.href = "{!! route('tools.backup.create') !!}";

        });

        document.getElementById('refresh-button').addEventListener('click', function(event) {
            event.preventDefault();
            showSwalLoading();
            window.location.href = "{!! route('tools.backup.index') !!}";
        });
    </script>
@endpush
