@extends('themes.main')

{{-- 1. DEFINE PAGE TITLE --}}
@section('title', 'Inventroy')


{{-- 2. DEFINE CONTENT HEADER (Breadcrumbs) --}}
@section('content')
    <link rel="stylesheet" href="{{ asset('css/finance.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">


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
                    <p class="sc-label text-muted font-weight-medium mb-0">Total Net Amount</p>
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
                    <p class="sc-label text-muted font-weight-medium mb-0">Total Revenue</p>
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
                    <p class="sc-label text-muted font-weight-medium mb-0">Total Profit</p>
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
                    <p class="sc-label text-muted font-weight-medium mb-0">Total Expenses</p>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card bg-gradient-info h-100" style="position: relative; left: 0px; top: 0px;">
                <div class="card-header border-0 ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title">
                        <i class="fas fa-th mr-1"></i>
                        Sales Graph
                    </h3>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas class="chart chartjs-render-monitor flex-grow-1" id="line-chart"
                        style="min-height: 250px; max-height: 250px; max-width: 100%; display: block;" width="739"
                        height="499"></canvas>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="row">
                        <div class="col-4 text-center">
                            <div class="knob-container" style="display: inline-block; width: 60px; height: 60px;">
                                <input type="text" class="knob" value="20" data-width="60" data-height="60"
                                    data-fgColor="#39CCCC" data-readonly="true" readonly="readonly">
                            </div>
                            <div class="text-white mt-2">Mail-Orders</div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="knob-container" style="display: inline-block; width: 60px; height: 60px;">
                                <input type="text" class="knob" value="50" data-width="60" data-height="60"
                                    data-fgColor="#39CCCC" data-readonly="true" readonly="readonly">
                            </div>
                            <div class="text-white mt-2">Online</div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="knob-container" style="display: inline-block; width: 60px; height: 60px;">
                                <input type="text" class="knob" value="30" data-width="60" data-height="60"
                                    data-fgColor="#39CCCC" data-readonly="true" readonly="readonly">
                            </div>
                            <div class="text-white mt-2">In-Store</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Sales</h3>
                        <a href="javascript:void(0);">View Report</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg">$18,230.00</span>
                            <span>Sales Over Time</span>
                        </p>
                        <p class="ml-auto d-flex flex-column text-right">
                            <span class="text-success">
                                <i class="fas fa-arrow-up"></i> 33.1%
                            </span>
                            <span class="text-muted">Since last month</span>
                        </p>
                    </div>

                    <div class="position-relative mb-4">
                        <canvas id="sales-chart" class="chart-canvas-responsive" height="200"></canvas>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                        <span class="mr-2">
                            <i class="fas fa-square text-primary"></i> This year
                        </span>
                        <span>
                            <i class="fas fa-square text-gray"></i> Last year
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-card">
        <div class="table-card-header">
            <div>
                <h3 class="table-card-title">Daily Transaction</h3>
            </div>
            <a href="{{ route('DailyTransction') }}" class="btn btn-primary table-card-action">
                <i class="fas fa-plus"></i> Add Transaction
            </a>
        </div>
        <div class="table-responsive">
            <table id="example2" class="table table-modern">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Perishable Type</th>
                        <th>Bundle Quantity</th>
                        <th>Bundle Size</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>



@endsection

@section('JS src')
    <script src="{{ asset('js/finance.js') }}"></script>
    <script>
        $(document).ready(function() {
            initFinance({

            });
        });
    </script>
@endsection
