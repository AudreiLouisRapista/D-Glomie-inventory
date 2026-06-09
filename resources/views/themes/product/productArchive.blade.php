@extends('themes.main')

{{-- 1. DEFINE PAGE TITLE --}}
@section('title', 'Product Archive')

{{-- 2. DEFINE CONTENT --}}
@section('content')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero-header.css') }}">

    <div class="inv-hero">
        <div class="inv-hero-text">
            <h1 class="m-0 font-weight-bold">Product Archive</h1>
            <p>List of archived/deleted product records</p>
        </div>

    </div>

    <div class="main-card">
        <div class="table">
            <table id="example2" class="table table-modern">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Perishable Type</th>
                        <th>Quantity</th>
                        <th>Pack Size</th>
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
    <script src="{{ asset('js/productArchive.js') }}"></script>
    <script>
        $(document).ready(function() {
            initProductArchive({
                viewProductArchiveUrl: "{{ route('view_product_archive') }}",
                restoreProductUrl: "{{ url('Admin/restore-product') }}",
                forceDeleteProductUrl: "{{ url('Admin/force-delete-product') }}"
            });
        });
    </script>
@endsection
