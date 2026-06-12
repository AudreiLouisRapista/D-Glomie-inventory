@extends('themes.main')

{{-- 1. DEFINE PAGE TITLE --}}
@section('title', 'Inventroy')


{{-- 2. DEFINE CONTENT HEADER (Breadcrumbs) --}}
@section('content')
    <link rel="stylesheet" href="{{ asset('css/finance.css') }}">

    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold">Financial Management</h1>
        <p> Manage and monitor your finance with ease</p>
    </div>

    <!-- Info boxes -->
    <div class="row">

        {{-- Total Inventory Record --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card sc sc-inventory shadow-sm h-100 modern-border-primary">
                <div class="card-body sc-body">
                    <div class="sc-top d-flex align-items-center mb-2">
                        <div class="sc-icon-wrap bg-soft-primary text-primary mr-3">
                            <i class="sc-icon bi bi-boxes"></i>
                        </div>
                        <h3 id="totalInventory" class="sc-value mb-0 font-weight-bold ml-auto display-4">
                            1</h3>
                    </div>
                    <p class="sc-label text-muted font-weight-medium mb-0">Total Inventory</p>
                </div>
            </div>
        </div>

        {{-- Available Stock --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card sc sc-stock shadow-sm h-100 modern-border-success">
                <div class="card-body sc-body">
                    <div class="sc-top d-flex align-items-center mb-2">
                        <div class="sc-icon-wrap bg-soft-success text-success mr-3">
                            <i class="sc-icon bi bi-bar-chart-fill"></i>
                        </div>
                        <h3 id="totalAvailableStock" class="sc-value mb-0 font-weight-bold ml-auto display-4">
                            1</h3>
                    </div>
                    <p class="sc-label text-muted font-weight-medium mb-0">Available Stock</p>
                </div>
            </div>
        </div>

        {{-- Low Stock --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card sc sc-low border-0 shadow-sm h-100 modern-border-warning">
                <div class="card-body sc-body">
                    <div class="sc-top d-flex align-items-center mb-2">
                        <div class="sc-icon-wrap bg-soft-warning text-warning mr-3">
                            <i class="sc-icon bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <h3 id="totalLowStock" class="sc-value mb-0 font-weight-bold ml-auto display-4">1
                        </h3>
                    </div>
                    <p class="sc-label text-muted font-weight-medium mb-0">Low Stock</p>
                </div>
            </div>
        </div>

        {{-- Out Of Stock --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card sc sc-out-of-stock border-0 shadow-sm h-100 modern-border-danger">
                <div class="card-body sc-body">
                    <div class="sc-top d-flex align-items-center mb-2">
                        <div class="sc-icon-wrap bg-soft-danger text-danger mr-3">
                            <i class="sc-icon bi bi-pie-chart-fill"></i>
                        </div>
                        <h3 id="totalOutOfStock" class="sc-value mb-0 font-weight-bold ml-auto display-4">
                            1</h3>
                    </div>
                    <p class="sc-label text-muted font-weight-medium mb-0">Out of Stock</p>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card card-primary ">
                <div class="card-header">
                    <h3 class="card-title">Area Chart</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="areaChart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 376px;"
                            width="751" height="499" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Bar Chart</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="barChart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 376px;"
                            width="751" height="499" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>

                <div>

                </div>
                <!-- /.card-body -->
            </div>
        </div>

    </div>
@endsection

@section('JS src')
    <script>
        var areaChartCanvas = document.getElementById('areaChart').getContext('2d');

        var areaChartData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                    label: 'Digital Goods',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: false,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: [28, 48, 40, 19, 86, 27, 90]
                },
                {
                    label: 'Electronics',
                    backgroundColor: 'rgba(210, 214, 222, 1)',
                    borderColor: 'rgba(210, 214, 222, 1)',
                    pointRadius: false,
                    pointColor: 'rgba(210,214,222,1)',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data: [65, 59, 80, 81, 56, 55, 40]
                }
            ]
        };

        var areaChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false
                    }
                }],
                yAxes: [{
                    gridLines: {
                        display: false
                    }
                }]
            }
        };

        new Chart(areaChartCanvas, {
            type: 'line',
            data: areaChartData,
            options: areaChartOptions
        });

        // BAR CHART
        var barChartCanvas = document.getElementById('barChart').getContext('2d');
        var barChartData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                    label: 'Electronics',
                    backgroundColor: 'rgba(210, 214, 222, 1)',
                    borderColor: 'rgba(210, 214, 222, 1)',
                    pointRadius: false,
                    pointColor: 'rgba(210,214,222,1)',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data: [65, 59, 80, 81, 56, 55, 40]
                },
                {
                    label: 'Digital Goods',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: false,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: [28, 48, 40, 19, 86, 27, 90]
                }
            ]
        };

        var barChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            datasetFill: false
        };

        new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        });
    </script>
@endsection
