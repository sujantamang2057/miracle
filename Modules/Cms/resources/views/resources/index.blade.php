@extends('cms::layouts.master')

@section('content_header')
    <section class="sc-breadcrumb">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('cms.home.index') }}">{{ __('cms::general.home') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('cms::general.resources') }}</li>
                </ol>
            </nav>
        </div>
    </section>
@endsection

@section('content')
    <div class="resources-page position-relative">
        <div id="loader" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <img src="{{ asset('img/loading.gif') }}" alt="Loading..." />
        </div>
        <div class="col-xxl-10 px-xxl-0 mx-auto px-5">
            <div class="heading d-flex align-items-end justify-content-between mb-sm-5 pb-sm-4 mb-3 pb-1">
                <div class="">
                    <div class="sc-title text-uppercase fn-raleway">{{ __('cms::general.resources') }}</div>
                    <p class="sc-subtitle fw-bold mb-0">{{ __('cms::general.files_to_download') }}</p>
                </div>
            </div>
            <select name="year" id="date-select">
                <option value="" class="year">ALL</option>
                @php
                    $years = range(date('Y'), 2022);
                @endphp
                @foreach ($years as $year)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }} class="year">
                        {{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="resourceListing">
            @include('cms::resources.resource_list')
        </div>
    </div>
@endsection

@push('page_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#date-select').on('change', function() {
                var selectedYear = $(this).val();
                var newUrl = '{{ route('cms.resources.index') }}' + (selectedYear ? '?year=' +
                    selectedYear : '');

                // Show the loader
                $('#loader').show();

                updateContent(newUrl);

                // Remove selected class from all options
                $('#date-select option').removeClass('selected');

                // Add selected class to the currently selected option
                $('#date-select option[value="' + selectedYear + '"]').addClass('selected');
            });

            var updateContent = (url) => {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('.resourceListing').html(response);
                        var cleanUrl = url.split('?')[0];
                        window.history.replaceState({
                            path: cleanUrl
                        }, '', cleanUrl);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    },
                    complete: function() {
                        // Hide the loader after the request is complete
                        $('#loader').hide();
                    }
                });
            };

            $('body').on('click', '.resourcePagination a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var year = $('#date-select').val();
                var newUrl = url.split('?')[0] + '?page=' + $.urlParam('page', url) + (year ? '&year=' +
                    year : '');

                // Show the loader
                // $('#loader').show();

                updateContent(newUrl);
            });

            $.urlParam = function(name, url) {
                var results = new RegExp('[?&]' + name + '=([^&#]*)').exec(url);
                return results ? decodeURIComponent(results[1].replace(/\+/g, ' ')) : null;
            };
        });
    </script>
@endpush
