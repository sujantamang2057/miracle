@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('image_regenerator') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('tools::common.image_regenerator') }} </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-4">
            {!! Form::open(['route' => 'tools.imageRegenerator.regenerate', 'id' => 'image_regenerator_form']) !!}
            <div class="card-body">
                @include('tools::image_regenerator.fields')
            </div>
            {!! Form::close() !!}
        </div>
        <div class="image_regenerator_wrapper">
            <div class="response m-3"></div>
        </div>
    </div>
@endsection

@include('tools::__partial.common')
@push('page_scripts')
    <script>
        function getImageColumn(e) {
            var elementTemp = e.currentTarget;
            var selectedModule = $(elementTemp).val();
            if (!selectedModule) {
                $('#image_name').find('option').not(':first').remove();
                return false;
            }
            $('#image_name').html("");
            showSwalLoading();
            resetResponse(event, "#imageRegeneratorSubmitBtn");
            $.ajax({
                type: 'POST',
                url: '{{ route('tools.imageRegenerator.getImageNames') }}',
                data: {
                    'module_name': selectedModule
                },
                success: function(response) {
                    if (response.html) {
                        $('#imageRegeneratorSubmitBtn').attr('disabled', false);
                        $('#image_name').html(response.html);
                    }
                }
            }).done(function() {
                hideSwalLoading();
            });
        }

        function regenerate(e) {
            e.preventDefault();
            var elementTemp = e.currentTarget,
                module_name = $('#module_name').val(),
                image_name = $('#image_name').val(),
                limit = $('#limit').val(),
                offset = $('#imageRegeneratorSubmitBtn').attr('offset');
            $(elementTemp).attr('disabled', true);

            if (module_name === '' || image_name === '') {
                window.swal.fire('{{ __('tools::common.messages.fill_required_fields') }}').then(function() {
                    $(elementTemp).attr('disabled', false);
                });
            }

            if (module_name && image_name) {
                showSwalLoading();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('tools.imageRegenerator.regenerate') }}',
                    data: 'module_name=' + module_name + '&image_name=' + image_name + '&offset=' + offset +
                        '&limit=' + limit,
                    success: function(response) {
                        if (response && response != " ") {
                            $('.response').html(response.html);
                            hideSwalLoading();
                            var totalCount = response.totalCount;
                            var oldCount = $('#imageRegeneratorSubmitBtn').attr('regenerated');
                            oldCount = (oldCount) ? parseInt(oldCount) : 0;
                            var showCount = oldCount + response.dataFetched;
                            $('#imageRegeneratorSubmitBtn').attr('regenerated',
                                showCount);
                            $('#imageRegeneratorSubmitBtn').attr('offset', showCount);
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
                        $(elementTemp).attr('disabled', false);
                    }
                });
            }
        }
    </script>
@endpush
