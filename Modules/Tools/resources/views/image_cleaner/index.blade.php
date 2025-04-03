@extends('tools::layouts.master')

@section('content')
    {{ Breadcrumbs::render('image_cleaner') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2"></div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card mb-6">
            <div class="card-header">
                <h4>{{ __('tools::image_cleaners.title') }}</h4>
            </div>

            <div class="card-body px-0">
                <div class="card card-primary card-outline card-outline-tabs mb-0">
                    <div class="card-header border-bottom-0 p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-temp-cleaner-tab" data-toggle="pill" href="#custom-tabs-temp-cleaner"
                                    role="tab" aria-controls="custom-tabs-temp-cleaner"
                                    aria-selected="true">{{ __('tools::image_cleaners.text.temp_image_cleaner') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-tabContent">
                            <div class="tab-pane fade active show" id="custom-tabs-temp-cleaner" role="tabpanel"
                                aria-labelledby="custom-tabs-temp-cleaner-tab">
                                {!! Form::open(['id' => 'tmp-image-cleaner-form', 'route' => 'tools.imageCleaner.cleanTemporaryFiles']) !!}
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group required mb-0">
                                            {!! Form::label('days_old', __('tools::image_cleaners.fields.days_old') . ':') !!}
                                            {!! Form::select('days_old', TMP_IMAGE_CLEAN_DAYS_OLD, null, [
                                                'class' => 'form-control',
                                                'id' => 'days_old',
                                            ]) !!}
                                            <p class="text-danger"></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 mt-2">
                                        <button type="submit" id="btnImgCleanerTmp" class="btn btn-warning mt-4"
                                            title="{{ __('tools::image_cleaners.text.temp_image_cleaner') }}">{{ __('tools::image_cleaners.btn.start_clean') }}</button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>

                    </div>
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
        $('#tmp-image-cleaner-form').on('submit', function(e) {
            e.preventDefault();
            window.swal
                .fire({
                    title: "{{ __('tools::image_cleaners.message.clear_temp_img') }}",
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
                        var daysOld = $('#days_old').val();
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('tools.imageCleaner.cleanTemporaryFiles') }}",
                            data: {
                                days_old: daysOld
                            },
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
        });
    </script>
@endpush
