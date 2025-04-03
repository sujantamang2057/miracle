@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('trash_data_purger') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('tools::trash_data_purgers.purge_trash_data') }} </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-6">
            {!! Form::open(['route' => 'tools.trashDataPurger.getTrashedData', 'id' => 'trash_data_purger_form']) !!}
            <div class="card-body px-0">
                <div class="row mb-2">
                    <div class="col-sm-6 form-group required">
                        {!! Form::label('model', __('tools::trash_data_purgers.fields.model') . ':') !!}
                        {!! Form::select('model', $models, null, [
                            'class' => 'form-control',
                            'placeholder' => __('common::crud.text.select_any'),
                            'id' => 'model',
                        ]) !!}
                    </div>
                    <div class="col-sm-6 form-group required">
                        {!! Form::label('duration', __('tools::trash_data_purgers.fields.duration') . ':') !!}
                        {!! Form::select('duration', $months, null, [
                            'class' => 'form-control',
                            'id' => 'duration',
                        ]) !!}
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i>
                    {{ __('tools::trash_data_purgers.buttons.get_trash_data') }}</button>
                @include('tools::__partial.reset')
            </div>
            {!! Form::close() !!}
            <div id="purgable-data-container" class="response mx-3">
            </div>
        </div>
    </div>
@endsection
@push('page_scripts')
    <script>
        function autoCloseMsg(msg, type) {
            var icon = typeof type != 'undefined' ? type : 'warning'
            window.swal.fire({
                title: msg,
                icon: icon,
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        }
        $(document).ready(function() {
            $('#model').on('change', function(e) {
                $('#purgable-data-container').html('');
            });

            $('#trash_data_purger_form').on('submit', function(e) {
                e.preventDefault();
                var model = $('#model').val();
                var duration = $('#duration').val();
                if (!model) {
                    autoCloseMsg('{{ __('tools::trash_data_purgers.messages.select_model') }}', 'warning');
                    return false;
                }
                if (!duration) {
                    autoCloseMsg('{{ __('tools::trash_data_purgers.messages.select_duration') }}',
                        'warning');
                    return false;
                }
                window.swal.showLoading();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('tools.trashDataPurger.getTrashedData') }}',
                    data: {
                        model: model,
                        duration: duration
                    },
                    success: function(data) {
                        $('#purgable-data-container').html(data);
                    }
                }).done(function() {
                    window.swal.close();
                });
            });
        });
    </script>
@endpush
