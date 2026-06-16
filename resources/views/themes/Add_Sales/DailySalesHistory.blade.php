@extends('themes.main')

@section('title', 'Inventory Sales History')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero-header.css') }}">

    <div class="inv-hero">
        <div class="inv-hero-text">
            <h1 class="m-0 font-weight-bold">Daily Sales History</h1>
            <p>List of all Daily sales transactions</p>
        </div>
    </div>


    <div class="main-card">
        <div class="table-card-header">
            <div>
                <h3 class="table-card-title">Sales Transaction List</h3>
                <p class="table-card-subtitle">Complete record of all daily sales.</p>
            </div>
        </div>
        <div class="table-responsive">
            <table id="example2" class="table table-modern">
                <thead>
                    <tr>
                        <th>Sales ID</th>
                        <th>Quantity Sold</th>
                        <th>Total Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

@endsection

@section('JS src')
    <script src="{{ asset('js/DailySalesHistory.js') }}"></script>
    <script>
        $(document).ready(function() {
            initSalesHistory({
                viewSalesHistoryUrl: "{{ route('view_sales_history') }}",
            });
        });
    </script>
@endsection
