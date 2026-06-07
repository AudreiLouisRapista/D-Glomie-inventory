@extends('themes.main')

{{-- 1. DEFINE PAGE TITLE --}}
@section('title', 'Inventroy Archive')

{{-- 2. DEFINE CONTENT --}}
@section('content')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero-header.css') }}">

    <div class="inv-hero">
        <div class="inv-hero-text">
            <h1 class="m-0 font-weight-bold">Inventory Archive</h1>
            <p>List of archived/deleted inventory records</p>
        </div>
    </div>

    <div class="main-card">
        <div class="table">
            <table id="example2" class="table table-modern">
                <thead>
                    <tr>
                        <th>Inventory ID</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Unit Price</th>
                        <th>Selling Price</th>
                        <th>Starting Quantity</th>
                        <th>New Quantity</th>
                        <th>Total Sold</th>
                        <th>Remaining Stock</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('JS src')
    <script src="{{ asset('js/inventoryArchive.js') }}"></script>
    <script>
        $(document).ready(function() {
            initInventoryArchive({
                viewInventoryArchiveUrl: "{{ route('view_inventory_archive') }}",
                restoreInventoryUrl: "{{ url('Admin/restore-inventory') }}",
                forceDeleteInventoryUrl: "{{ url('Admin/force-delete-inventory') }}"
            });
        });
    </script>
@endsection
