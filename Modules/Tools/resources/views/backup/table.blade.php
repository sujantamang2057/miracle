<div class="w-100">
    <table class="table-striped dt-table-hover dataTable table" id="backups-table">
        <thead>
            <th class="action-button-col">{{ __('tools::backups.actions') }}</th>
            <th class="date-col-long">{{ __('tools::backups.date') }}</th>
            <th class="filesize-col">{{ __('tools::backups.file_size') }}</th>
            <th>{{ __('tools::backups.file_name') }}</th>
        </thead>
        <tbody>
            @if ($backups)
                @foreach ($backups as $backup)
                    <tr>
                        <td>
                            {!! Form::open([
                                'route' => ['tools.backup.delete', ['disk' => $backup->diskName, 'file_name' => $backup->fileNameWithPath]],
                                'method' => 'delete',
                                'id' => 'deleteform_' . $loop->index,
                            ]) !!}
                            <div class='action-buttons'>
                                @if (checkToolsPermission('backup.download'))
                                    @if ($backup->downloadLink)
                                        <a href="{{ $backup->downloadLink }}" class="btn btn-default btn-sm" title="{{ __('tools::backups.download') }}">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                @endif
                                @if (checkToolsPermission('backup.delete'))
                                    @if ($backup->deleteLink)
                                        {!! Form::button('<i class="fas fa-trash"></i>', [
                                            'type' => 'submit',
                                            'class' => 'btn btn-danger btn-sm',
                                            'title' => __('tools::backups.delete'),
                                            'onclick' => "return confirmDeleteBackup(event, 'deleteform_$loop->index')",
                                        ]) !!}
                                    @endif
                                @endif
                            </div>
                            {!! Form::close() !!}
                        </td>
                        <td>{!! $backup->lastModified !!}</td>
                        <td>{!! $backup->fileSize !!}</td>
                        <td>{!! $backup->fileNameOnly !!}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td valign="top" colspan="4" class="dataTables_empty">
                        {{ __('common::crud.messages.empty_backup_file') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@include('common::__partial.swal-scripts')
@push('page_scripts')
    <script type="text/javascript">
        function confirmDeleteBackup(event, formId) {
            event.preventDefault();
            window.swal.fire({
                title: "{{ __('common::crud.messages.are_you_sure_to_delete') }}",
                text: "{{ __('common::crud.messages.not_able_to_recover_data') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "{{ __('common::crud.messages.yes_delete') }}",
                cancelButtonText: "{{ __('common::crud.messages.no_keep_it') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading spinner
                    window.swal.fire({
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            window.swal.showLoading();
                        }
                    });
                    if (formId !== undefined) {
                        var form = document.getElementById(formId);
                        var formData = new FormData(form);
                        location.reload();
                        $.ajax({
                            url: form.action,
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                location.reload();
                            },
                            error: function(xhr) {
                                window.swal.close();
                                window.swal.fire({
                                    title: "{{ __('common::crud.messages.error_occurred') }}",
                                    text: xhr.responseText ||
                                        "{{ __('common::crud.messages.try_again') }}",
                                    icon: 'error',
                                    confirmButtonText: "{{ __('common::crud.ok') }}"
                                });
                            }
                        });
                    }
                }
            });
        }
    </script>
@endpush
