@extends('themes.main')

{{-- 1. DEFINE PAGE TITLE --}}
@section('title', 'Inventroy')


{{-- 2. DEFINE CONTENT HEADER (Breadcrumbs) --}}
@section('content')

    <link rel="stylesheet" href="{{ asset('css/inventory.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold">Inventory Management</h1>
        <p> Manage and monitor your inventory with ease</p>
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
                            {{ $totalInventory }}</h3>
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
                            {{ $totalAvailableStock }}</h3>
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
                        <h3 id="totalLowStock" class="sc-value mb-0 font-weight-bold ml-auto display-4">{{ $totalLowStock }}
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
                            {{ $totalOutOfStock }}</h3>
                    </div>
                    <p class="sc-label text-muted font-weight-medium mb-0">Out of Stock</p>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->




    <div class="main-card">
        <div class="table-card-header">
            <div>
                <h3 class="table-card-title">Inventory List</h3>
                <p class="table-card-subtitle">Current stock levels, pricing, and status by product.</p>
            </div>

            <div class="flex gap-3">
                <button type="button" class="btn btn-primary table-card-action" data-toggle="modal"
                    data-target="#registerProductModal">
                    <i class="fas fa-plus"></i> Add Inventory
                </button>
                <button type="button" class="btn btn-success table-card-action" data-toggle="modal"
                    data-target="#salesInventoryModal">
                    <i class="bi bi-receipt"></i> Add Sales
                </button>
            </div>
        </div>
        <div class="table-responsive">
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
                        <th>Total Quantity Sold</th>
                        <th>Remaining Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>


    {{-- REGETRATION Inventory MODAL  --}}
    <div class="modal fade" id="registerProductModal" tabindex="-1" role="dialog"
        aria-labelledby="registerProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content shadow-lg modern-form-modal">

                {{-- Modal Header --}}
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title font-weight-bold" id="registerProductModalLabel">
                        <i class="fas fa-box-open mr-2"></i> Register New Inventory
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body p-4">
                    <form id="registerProductForm" method="POST" action="{{ route('save_inventory') }}"
                        enctype="multipart/form-data">
                        @csrf

                        {{-- Basic Information --}}
                        <div class="mb-4">
                            <p class="form-section-title">Basic Information
                            </p>
                            <div class="form-row">

                                {{-- Category --}}
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold" style="color: #475569;">Category</label>
                                    <div class="input-group" style="flex-wrap: nowrap;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light h-100"
                                                style="border-radius: 10px 0 0 10px;">
                                                <i class="fas fa-tag text-muted"></i>
                                            </span>
                                        </div>
                                        <select id="categorySelect" name="category" class="form-control bg-light select2"
                                            style="border-radius: 0 10px 10px 0; width: 1% !important; flex: 1 1 auto;">
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- Product --}}
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold" style="color: #475569;">Product</label>
                                    <div class="input-group" style="flex-wrap: nowrap;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light h-100"
                                                style="border-radius: 10px 0 0 10px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </span>
                                        </div>
                                        <select id="productSelect" name="product" class="form-control select2"
                                            style="border-radius: 0 10px 10px 0; width: 1% !important; flex: 1 1 auto;">
                                            <option value="">-- Select Category First --</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Price & Quantity --}}
                        <p class="form-section-title">Price &
                            Quantity</p>
                        <div class="form-row mb-4">

                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Cost Price</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">₱</span>
                                    </div>
                                    <input type="number" name="cost_price" id="costPriceInput" class="form-control"
                                        step="0.01" placeholder="0.00" required>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Selling Price</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">₱</span>
                                    </div>
                                    <input type="number" name="selling_price" id="sellingPriceInput"
                                        class="form-control" step="0.01" placeholder="0.00" required>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Starting Qty.</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">#</span>
                                    </div>
                                    <input type="number" name="quantity" id="quantityInput" class="form-control"
                                        placeholder="0" min="1" required>
                                </div>
                            </div>

                        </div>

                        {{-- Footer Buttons --}}
                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-light px-4 mr-2" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-5 font-weight-bold shadow-sm">
                                Save Product
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- Update Inventory MODAL  --}}
    <div class="modal fade" id="updateInventoryModal" tabindex="-1" role="dialog"
        aria-labelledby="updateInventoryModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content shadow-lg modern-form-modal">

                {{-- Modal Header --}}
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title font-weight-bold" id="updateInventoryModalLabel">
                        <i class="fas fa-box-open mr-2"></i> Update Inventory
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body p-4">
                    <form id="updateProductForm" method="POST" action="{{ route('update_inventory') }}"
                        enctype="multipart/form-data">
                        @csrf

                        {{-- Basic Information --}}
                        <div class="mb-4">
                            <p class="form-section-title">Basic Information
                            </p>
                            <div class="form-row">
                                <input type="hidden" name="id" id="edit_inventory_id">

                                {{-- Category --}}
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold" style="color: #475569;">Category</label>
                                    <div class="input-group" style="flex-wrap: nowrap;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light h-100"
                                                style="border-radius: 10px 0 0 10px;">
                                                <i class="fas fa-tag text-muted"></i>
                                            </span>
                                        </div>
                                        <select id="edit_category" name="category_id"
                                            class="form-control bg-light select2"
                                            style="border-radius: 0 10px 10px 0; width: 1% !important; flex: 1 1 auto;">
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- Product --}}
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold" style="color: #475569;">Product</label>
                                    <div class="input-group" style="flex-wrap: nowrap;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light h-100"
                                                style="border-radius: 10px 0 0 10px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </span>
                                        </div>
                                        <select id="edit_product_name" name="product_id" class="form-control select2"
                                            style="border-radius: 0 10px 10px 0; width: 1% !important; flex: 1 1 auto;">
                                            <option value="">-- Select Category First --</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Price --}}
                        <p class="form-section-title">Price</p>
                        <div class="form-row mb-4">

                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Selling Price</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">₱</span>
                                    </div>
                                    <input type="number" name="selling_price" id="edit_selling_price"
                                        class="form-control" step="0.01" placeholder="0.00" required>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-light px-4 mr-2" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-5 font-weight-bold shadow-sm">
                                Save Product
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- Save Inventory Sales MODAL  --}}
    <div class="modal fade" id="salesInventoryModal" tabindex="-1" role="dialog"
        aria-labelledby="salesInventoryModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content shadow-lg modern-form-modal">

                {{-- Modal Header --}}
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title font-weight-bold" id="salesInventoryModalLabel">
                        <i class="fas fa-box-open mr-2"></i> Save Inventory Sales
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body p-4">
                    <form id="saveSalesForm" method="POST" action="{{ route('add_sale_record') }}"
                        enctype="multipart/form-data">
                        @csrf

                        {{-- Basic Information --}}
                        <div class="mb-4">
                            <p class="form-section-title">Basic Information</p>
                            <div class="form-row">
                                <input type="hidden" name="id" id="sales_inventory_id">

                                {{-- Category --}}
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold" style="color: #475569;">Category</label>
                                    <div class="input-group" style="flex-wrap: nowrap;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light h-100"
                                                style="border-radius: 10px 0 0 10px;">
                                                <i class="fas fa-tag text-muted"></i>
                                            </span>
                                        </div>
                                        <select id="sales_category" name="category_id"
                                            class="form-control bg-light select2"
                                            style="border-radius: 0 10px 10px 0; width: 1% !important; flex: 1 1 auto;">
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Product --}}
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold" style="color: #475569;">Product</label>
                                    <div class="input-group" style="flex-wrap: nowrap;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light h-100"
                                                style="border-radius: 10px 0 0 10px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </span>
                                        </div>
                                        <select id="sales_product_name" name="product_id" class="form-control select2"
                                            style="border-radius: 0 10px 10px 0; width: 1% !important; flex: 1 1 auto;">
                                            <option value="">-- Select Category First --</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Amount --}}
                        <p class="form-section-title">Amount & Quantity</p>
                        <div class="form-row mb-4">

                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Total Quantity</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">#</span>
                                    </div>
                                    <input type="number" name="total_quantity" id="sales_total_quantity"
                                        class="form-control" step="1" placeholder="0" required>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Total Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">₱</span>
                                    </div>
                                    <input type="number" name="total_amount" id="sales_total_amount"
                                        class="form-control" step="0.01" placeholder="0.00" required readonly>
                                </div>
                            </div>


                        </div>

                        {{-- Footer Buttons --}}
                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-light px-4 mr-2" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-5 font-weight-bold shadow-sm">
                                Save Sales
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection

@section('JS src')
    <script src="{{ asset('js/inventory.js') }}"></script>
    <script>
        $(document).ready(function() {
            initInventory({
                viewInventoryUrl: "{{ route('view_inventory') }}",
                getProductsUrl: "{{ route('get_products_by_category') }}",
                saveInventoryUrl: "{{ route('save_inventory') }}",
                softDeleteInventoryUrl: "{{ url('Admin/soft-delete-inventory') }}",
                getInventoryByProductUrl: "{{ url('Admin/get-inventory-by-product') }}",
                addSaleUrl: "{{ route('add_sale_record') }}",
            });
        });
    </script>
@endsection
