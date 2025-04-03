@extends('tools::layouts.master')

@section('content')
    {{ Breadcrumbs::render('space_report') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            </div>
        </div>
    </section>

    <div class="container-fluid content px-3">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0">{{ __('tools::common.space_report') }}</h4>
                    <a class="btn btn-warning btn-sm btn-sm no-corner ml-3" href="{{ route('tools.spaceReport.index') }}">
                        {{ __('tools::backups.refresh') }}
                    </a>
                </div>
            </div>

            <div class="card-body px-0">
                <div class="mb-3">
                    <section id='listing' class="content px-2">
                        <!-- statistics -->
                        <div class="row statistics mb-4" style="row-gap: 1rem;">
                            <div class="col-xl-3 col-md-3 col-sm-4 col-xs-6">
                                <div class="small-box d-flex flex-column h-100 bg-red">
                                    <div class="inner pb-0">
                                        <h3>{{ $totalSize }}MB</h3>
                                        <p class="mb-0">{{ __('tools::space_report.total') }}</p>
                                    </div>
                                </div>
                            </div>
                            @foreach ($data as $key => $item)
                                @php
                                    $labels[] = $item['name'];
                                    $reportData[] = $item['size'];
                                    $colors[] = $item['color'];
                                @endphp
                                <div class="col-xl-3 col-md-3 col-sm-4 col-xs-6">
                                    <div class="small-box d-flex flex-column h-100 bg-{{ $item['bg_color'] }}">
                                        <div class="inner pb-0">
                                            <h3 class="text-white">{{ $item['size'] }}MB</h3>
                                            <p class="mb-0 text-white">{{ $item['name'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div>
                            <div style="width: 80%; height:500px; margin: auto;">
                                <canvas style="margin: 0 auto; width: 500px" id="doughNut"></canvas>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="row mx-0">
                    <div class="col-sm-12">@include('flash::message')</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('third_party_scripts')
    <script src="{{ asset(THIRD_PARTY_ASSETS_DIR_PATH . '/chartJs/chart.js') }}"></script>
@endpush
@push('page_scripts')
    <script>
        var data = @json($reportData),
            labels = @json($labels),
            colors = @json($colors);

        $(document).on('click', 'a.refresh-space-usage-report', function(e) {
            container: '#listing'
        });

        var chartData = {
            labels: labels,
            datasets: [{
                label: ' MB',
                data: data,
                backgroundColor: colors,
                hoverOffset: 4
            }]
        };

        var doughNutCanvas = $('#doughNut').get(0).getContext('2d');
        var chart = new Chart(doughNutCanvas, {
            type: 'doughnut',
            data: chartData,
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.formattedValue + context.dataset.label;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
