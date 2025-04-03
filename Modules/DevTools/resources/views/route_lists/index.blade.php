@extends('sys::layouts.master')

@section('content')
    {{ Breadcrumbs::render('route_lists') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('devtools::common.route_lists') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col">
                        <button class="btn btn-sm bg-light is-checked border" data-filter="all">{{ __('devtools::route_lists.btn.all') }}</button>
                        <button class="btn btn-sm bg-light border" data-filter="custom">{{ __('devtools::route_lists.btn.custom') }}</button>
                        <button class="btn btn-sm bg-light border" data-filter="vendor">{{ __('devtools::route_lists.btn.vendor') }}</button>
                    </div>
                </div>
                <div class="">
                    <div id="route-list-container">
                        @include('devtools::route_lists.list', ['routes' => $routes])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('common::__partial.swal-scripts')
@push('page_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var buttons = $('.btn');
            buttons.on('click', function() {
                var filters = $(this).data('filter');
                $('.btn').removeClass('is-checked');
                $(this).addClass('is-checked');

                //show loading
                showSwalLoading();

                $.ajax({
                    url: '{{ route('devtools.dev.route') }}',
                    type: 'POST',
                    data: {
                        type: filters,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#route-list-container').html(response.html);
                        hideSwalLoading();
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            });
        });
    </script>
@endpush
