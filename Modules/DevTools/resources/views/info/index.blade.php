@extends('sys::layouts.master')

@section('content')
    <style type="text/css" media="screen">
        .php-info pre {
            margin: 0;
            font-family: monospace;
        }

        .php-info a:link {
            color: #009;
            text-decoration: none;
            background-color: #ffffff;
        }

        .php-info a:hover {
            text-decoration: underline;
        }

        .php-info table {
            border-collapse: collapse;
            border: 0;
            width: 100%;
            box-shadow: 1px 2px 3px #ccc;
        }

        .php-info .center {
            text-align: center;
        }

        .php-info .center table {
            margin: 0 auto 1em auto;
            text-align: left;
        }

        .php-info .center th {
            text-align: center !important;
        }

        .php-info td {
            border: 1px solid #666;
            font-size: 75%;
            vertical-align: baseline;
            padding: 4px 5px;
        }

        .php-info th {
            border: 1px solid #666;
            font-size: 75%;
            vertical-align: baseline;
            padding: 4px 5px;
        }

        .php-info h1 {
            font-size: 150%;
        }

        .php-info h2 {
            font-size: 125%;
        }

        .php-info .p {
            text-align: left;
        }

        .php-info .e {
            background-color: #ccf;
            width: 50px;
            font-weight: bold;
        }

        .php-info .h {
            background-color: #99c;
            font-weight: bold;
        }

        .php-info .v {
            background-color: #ddd;
            max-width: 50px;
            overflow-x: auto;
            word-wrap: break-word;
        }

        .php-info .v i {
            color: #999;
        }

        .php-info img {
            float: right;
            border: 0;
        }

        .php-info hr {
            width: 100%;
            background-color: #ccc;
            border: 0;
            height: 1px;
        }
    </style>

    {{ Breadcrumbs::render('php_info') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">{{ __('devtools::common.php_info') }}</h4>
            </div>

            <div class="card-body px-0">
                <div class="d-flex mb-3">
                    <div class="php-info w-100">
                        @php
                            ob_start();
                            phpinfo();
                            $phpInfo = ob_get_contents();
                            ob_end_clean();
                            $phpInfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpInfo);
                            echo $phpInfo;
                        @endphp
                    </div>
                </div>

                <div class="row mx-0">
                    <div class="col-sm-12">@include('flash::message')</div>
                </div>
            </div>
        </div>
    </div>
@endsection
