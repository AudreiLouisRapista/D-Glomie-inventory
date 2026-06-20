@extends('themes.main')

@section('title', 'Finance')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/finance.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    {{-- Page Header & Filters --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div class="py-2">
            <h1 class="m-0 font-weight-bold text-dark h3">Financial Management</h1>
            <p class="text-muted mb-0">Manage and monitor your finance with ease</p>
        </div>

        {{-- Page Filter --}}
        <div class="d-flex align-items-center my-2" style="gap: 8px;">
            <select id="monthFilter" class="form-control bg-white shadow-sm border-0">
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ \DateTime::createFromFormat('!m', $m)->format('F') }}
                    </option>
                @endforeach
            </select>
            <select id="yearFilter" class="form-control bg-white shadow-sm border-0">
                @foreach (range(now()->year - 3, now()->year) as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary shadow-sm" id="applyPageFilter">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
        </div>
    </div>

    {{-- Refined Pastel Info Cards --}}
    <div class="row">

        {{-- Total Net Amount (Blue Theme) --}}
        <div class="col-lg-3 col-sm-6 mb-4">
            <div class="card h-100 border-0 shadow-sm custom-stat-card">
                <div class="card-header border-0 stat-hdr-blue d-flex align-items-center">
                    <i class="fas fa-wallet mr-2"></i>
                    <span class="font-weight-bold tracking-wide">Total Net Amount</span>
                </div>
                <div class="card-body d-flex flex-column justify-content-between p-3">
                    <h3 id="totalNetAmount" class="font-weight-bold mb-1 text-dark h4">
                        ₱{{ number_format($summary->total_net_amount, 2) }}
                    </h3>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <span class="text-xs text-muted font-weight-medium">Since last selection</span>
                        <span class="badge stat-badge-blue py-1 px-2">Active</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Revenue (Green Theme) --}}
        <div class="col-lg-3 col-sm-6 mb-4">
            <div class="card h-100 border-0 shadow-sm custom-stat-card">
                <div class="card-header border-0 stat-hdr-green d-flex align-items-center">
                    <i class="fas fa-chart-line mr-2"></i>
                    <span class="font-weight-bold tracking-wide">Total Revenue</span>
                </div>
                <div class="card-body d-flex flex-column justify-content-between p-3">
                    <h3 id="totalRevenue" class="font-weight-bold mb-1 text-dark h4">
                        ₱{{ number_format($summary->total_revenue, 2) }}
                    </h3>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <span class="text-xs text-muted font-weight-medium">Since last selection</span>
                        <span class="badge stat-badge-green py-1 px-2">
                            <i class="fas fa-caret-up mr-1"></i>Inflow
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Profit (Yellow Theme) --}}
        <div class="col-lg-3 col-sm-6 mb-4">
            <div class="card h-100 border-0 shadow-sm custom-stat-card">
                <div class="card-header border-0 stat-hdr-yellow d-flex align-items-center">
                    <i class="fas fa-coins mr-2"></i>
                    <span class="font-weight-bold tracking-wide">Total Profit</span>
                </div>
                <div class="card-body d-flex flex-column justify-content-between p-3">
                    <h3 id="totalProfit" class="font-weight-bold mb-1 text-dark h4">
                        ₱{{ number_format($summary->total_profit, 2) }}
                    </h3>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <span class="text-xs text-muted font-weight-medium">Since last selection</span>
                        <span class="badge stat-badge-yellow py-1 px-2">Net Earnings</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Expenses (Red Theme) --}}
        <div class="col-lg-3 col-sm-6 mb-4">
            <div class="card h-100 border-0 shadow-sm custom-stat-card">
                <div class="card-header border-0 stat-hdr-red d-flex align-items-center">
                    <i class="fas fa-credit-card mr-2"></i>
                    <span class="font-weight-bold tracking-wide">Total Expenses</span>
                </div>
                <div class="card-body d-flex flex-column justify-content-between p-3">
                    <h3 id="totalExpenses" class="font-weight-bold mb-1 text-dark h4">
                        ₱{{ number_format($summary->total_expenses, 2) }}
                    </h3>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <span class="text-xs text-muted font-weight-medium">Since last selection</span>
                        <span class="badge stat-badge-red py-1 px-2">
                            <i class="fas fa-caret-down mr-1"></i>Outflow
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- /.row --}}

    {{-- Charts Section --}}
    <div class="row">
        {{-- Line Chart — Net Income Trend --}}
        <div class="col-md-6 mb-4">
            <div class="card bg-gradient-info h-100 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header border-0 d-flex justify-content-between align-items-center bg-transparent">
                    <h3 class="card-title font-weight-bold mb-0 text-white">
                        <i class="fas fa-chart-line mr-1"></i> Net Income Trend
                    </h3>
                    <div class="btn-group btn-group-sm shadow-sm">
                        <button type="button" class="btn btn-light graph-filter-btn active" data-filter="day">Day</button>
                        <button type="button" class="btn btn-light graph-filter-btn" data-filter="week">Week</button>
                    </div>
                </div>
                <div class="card-body d-flex flex-column">
                    <canvas class="chart chartjs-render-monitor flex-grow-1" id="line-chart"
                        style="min-height: 250px; max-height: 250px; max-width: 100%; display: block;"></canvas>
                </div>
            </div>
        </div>

        {{-- Bar Chart — Purchases vs Sales --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header border-0 d-flex justify-content-between align-items-center bg-white">
                    <h3 class="card-title font-weight-bold mb-0 text-dark">
                        <i class="fas fa-chart-bar mr-1"></i> Purchases vs Sales
                    </h3>
                    <div class="d-flex flex-row" style="gap: 10px;">
                        <span class="text-muted" style="font-size: 0.85rem;">
                            <i class="fas fa-square text-primary"></i> Sales
                        </span>
                        <span class="text-muted" style="font-size: 0.85rem;">
                            <i class="fas fa-square text-gray"></i> Purchases
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="position-relative mb-2">
                        <canvas id="sales-chart" height="220"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daily Transaction Table --}}
    <div class="main-card card border-0 shadow-sm p-3" style="border-radius: 12px;">
        <div class="table-card-header d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="table-card-title font-weight-bold text-dark h5 mb-0">Daily Transaction</h3>
            </div>
            <a href="{{ route('dailyReport') }}" class="btn btn-primary shadow-sm table-card-action">
                <i class="fas fa-plus mr-1"></i> Add Transaction
            </a>
        </div>
        <div class="table-responsive">
            <table id="example2" class="table table-modern w-100">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Gross Sales</th>
                        <th>Total Expenses</th>
                        <th>Net Income</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

@endsection

@section('JS src')
    <script src="{{ asset('js/finance.js') }}"></script>
    <script>
        $(document).ready(function() {
            initFinance({
                viewFinanceUrl: "{{ route('view_finance') }}",
                getGraphDataUrl: "{{ route('get_graph_data') }}",
                viewReportUrl: "{{ url('Admin/view-report-details') }}",
                initialMonth: {{ $month }},
                initialYear: {{ $year }},
            });
        });
    </script>
@endsection
