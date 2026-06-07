@extends('themes.main')

@section('title', 'Inventory Sales History')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/inventory.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold">Inventory Sales History</h1>
        <p>All inventory sales transactions</p>
    </div>

    <div class="main-card">
        <div class="table-card-header">
            <div>
                <h3 class="table-card-title">Sales Transaction List</h3>
                <p class="table-card-subtitle">Complete record of all inventory sales.</p>
            </div>
        </div>
        <div class="table-responsive">
            <table id="example2" class="table table-modern">
                <thead>
                    <tr>
                        <th>Sale ID</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Selling Price</th>
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
    <script src="{{ asset('js/inventorySalesHistory.js') }}"></script>
    <script>
        $(document).ready(function() {
            initSalesHistory({
                viewSalesHistoryUrl: "{{ route('view_sales_history') }}",
            });
        });
    </script>
@endsection
