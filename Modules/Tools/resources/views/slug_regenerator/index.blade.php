@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('slug_regenerator') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('tools::common.slug_regenerator') }} </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-4">
            {!! Form::open(['route' => 'tools.slugRegenerator.regenerate', 'id' => 'slug_regenerator_form']) !!}
            <div class="card-body">
                <div class="row">
                    @include('tools::slug_regenerator.fields')
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <div class="slug_regenerator_wrapper">
            <div class="response m-3"></div>
        </div>
    </div>
@endsection

@include('tools::__partial.common')
@push('page_scripts')
    <script>
        function regenerate(e) {
            e.preventDefault();
            var elementTemp = e.currentTarget,
                module_name = $('#module_name').val(),
                limit = $('#limit').val(),
                offset = $('#slugRegeneratorSubmitBtn').attr('offset');

            $(elementTemp).attr('disabled', true);
            if (module_name === '' || limit <= 0) {
                window.swal.fire('{{ __('tools::common.messages.fill_required_fields') }}').then(function() {
                    $(elementTemp).attr('disabled', false);
                });
                return false;
            }

            if (module_name) {
                showSwalLoading();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('tools.slugRegenerator.regenerate') }}',
                    data: 'module_name=' + module_name + '&offset=' + offset + '&limit=' + limit,
                    success: function(response) {
                        if (response && response != " ") {
                            $('.response').html(response.html);
                            hideSwalLoading();
                            var totalCount = response.totalCount;
                            var oldCount = $('#slugRegeneratorSubmitBtn').attr('regenerated');
                            oldCount = (oldCount) ? parseInt(oldCount) : 0;
                            var showCount = oldCount + response.slugRegenerated;
                            $('#slugRegeneratorSubmitBtn').attr('regenerated',
                                showCount);
                            $('#slugRegeneratorSubmitBtn').attr('offset', showCount);
                            if (showCount >= totalCount) {
                                var infoMsg = showCount + '/' + totalCount +
                                    ' {{ __('tools::common.messages.completed') }}';
                            } else {
                                $(elementTemp).attr('disabled', false);
                                var infoMsg = showCount + '/' + totalCount +
                                    ' {{ __('tools::common.messages.done') }}';
                            }
                            autoCloseSwal(infoMsg);
                        } else {
                            $(elementTemp).attr('disabled', false);
                            hideSwalLoading();
                            $('.moduleTitle').hide();
                            $('.data').hide();
                            infoMsg = '{{ __('tools::common.messages.data_donot_exist') }}';
                            autoCloseSwal(infoMsg, 'error');
                        }
                    },
                    error: function(data) {
                        var errors = (data && data.responseJSON) ? data.responseJSON.errors : null;
                        if (errors) {
                            for (const prop in errors) {
                                $('#' + prop).parent('.form-group').removeClass('has-success').addClass(
                                    'has-error');
                                $('#' + prop).addClass('is-invalid');
                                $('#' + prop).siblings('p.text-danger').text(errors[prop][0]);
                            }
                        }
                        $(elementTemp).attr('disabled', false);
                        hideSwalLoading();
                    }
                });
            }
        }
    </script>
@endpush
