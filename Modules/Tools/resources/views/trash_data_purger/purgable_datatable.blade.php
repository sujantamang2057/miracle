@if (!empty($purgableData) && $purgableData->count())
    <div>
        <button type="button" class="btn btn-danger mb-1" id="delete-btn" data-model="{{ $modelName }}"><i class="fas fa-trash"></i>
            {{ __('tools::trash_data_purgers.buttons.purge') }}</button>
    </div>
    <table class="table-striped table">
        <thead>
            <tr>
                <th class="checkbox-col"><input type="checkbox" id="select-all"></th>
                <th>{{ __('tools::trash_data_purgers.fields.id') }}</th>
                <th>{{ __('tools::trash_data_purgers.fields.title') }}</th>
                <th class="datetime-col">{{ __('common::crud.fields.deleted_at') }}</th>
                <th>{{ __('common::crud.fields.deleted_by') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purgableData as $data)
                <tr>
                    <td><input type="checkbox" name="purgable_ids[]" value="{{ $data->getKey() }}"></td>
                    <td>{{ $data->getKey() }}</td>
                    <td>{{ $data->$titleField }}</td>
                    <td>{{ $data->deleted_at }}</td>
                    <td>{{ $data->deleted_by ? getUserDataById($data->deleted_by) : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#select-all').on('click', function() {
                $('input[name="purgable_ids[]"]').prop('checked', this.checked);
            });
            $('#delete-btn').on('click', function(e) {
                var modelName = $(this).attr('data-model');
                var checkedIds = $('input[name="purgable_ids[]"]:checked').map(function() {
                    return this.value;
                }).get();

                if (checkedIds.length) {
                    window.swal
                        .fire({
                            title: "{{ __('tools::trash_data_purgers.messages.confirm') }}",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: "{{ __('common::general.yes') }}",
                            cancelButtonText: "{{ __('common::general.no') }}",
                        })
                        .then((result) => {
                            if (result.value) {
                                $.ajax({
                                    type: 'POST',
                                    url: '{{ route('tools.trashDataPurger.purgeTrashedData') }}',
                                    contentType: 'application/json',
                                    data: JSON.stringify({
                                        model: '{{ $model }}',
                                        purgable_ids: checkedIds
                                    }),
                                    success: function(data) {
                                        if (data.type != 'error') {
                                            if ($('#select-all').is(':checked') ||
                                                ($('input[name="purgable_ids[]"]:checked')
                                                    .length == $(
                                                        'input[name="purgable_ids[]"]')
                                                    .length)) {
                                                $('#purgable-data-container').html('');
                                            } else {
                                                $('input[name="purgable_ids[]"]:checked')
                                                    .parents('tr').remove();
                                            }
                                        }
                                        autoCloseMsg(data.message, data.type);
                                    },
                                    error: function(xhr, status, error) {
                                        autoCloseMsg(
                                            '{{ __('tools::trash_data_purgers.messages.error') }}',
                                            'error');
                                    }
                                });
                            }
                        });
                } else {
                    autoCloseMsg('{{ __('tools::trash_data_purgers.messages.select_data_msg') }}');
                }
            });
        });
    </script>
@else
    <div class="alert alert-danger mb-3" role="alert">
        {{ __('common::messages.model_data_not_found', ['model' => $modelName]) }}
    </div>
@endif
