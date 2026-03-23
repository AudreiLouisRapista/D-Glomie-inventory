@extends('themes.main')

{{-- 1. DEFINE PAGE TITLE --}}
@section('title', 'User Profile')


{{-- 2. DEFINE CONTENT HEADER (Breadcrumbs) --}}
@section('content')

    <link rel="stylesheet" href="{{ asset('css/inventory.css') }}">
    <div class="col-sm-6">
        <h1>Inventory Management</h1>
        <p> Manage and monitor your inventory with ease</p>
    </div>
    <!-- Info boxes -->
    <div class="row g-3">

        {{-- Total Inventory Record --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card sc sc-inventory shadow-sm h-100 modern-border-primary">
                <div class="card-body sc-body">
                    <div class="sc-top d-flex align-items-center mb-2">
                        <div class="sc-icon-wrap bg-soft-primary text-primary mr-3">
                            <i class="sc-icon bi bi-boxes"></i>
                        </div>
                        <h3 class="sc-value mb-0 font-weight-bold ml-auto display-4">53</h3>
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
                        <h3 class="sc-value mb-0 font-weight-bold ml-auto display-4">53</h3>
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
                        <h3 class="sc-value mb-0 font-weight-bold ml-auto display-4">44</h3>
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
                        <h3 class="sc-value mb-0 font-weight-bold ml-auto display-4">65</h3>
                    </div>
                    <p class="sc-label text-muted font-weight-medium mb-0">Out of Stock</p>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->

    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Inventory List</h3>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#registerProductModal"
                style="float: right;">
                Add inventory
            </button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
                <thead style="text-align: center; background-color: #f8fafc;">
                    <tr>
                        <th>Inventory ID</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Cost</th>
                        <th>Selling Price</th>
                        <th>Starting Quantity</th>
                        <th>New Quantity</th>
                        <th>Total Sold</th>
                        <th>Remaining Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
    <!-- /.card-body -->
    </div>

    {{-- REGETRATION PRODUCT MODAL  --}}
    <div class="modal fade" id="registerProductModal" tabindex="-1" role="dialog"
        aria-labelledby="registerProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">

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
                    <form id="registerProductForm" method="POST" enctype="multipart/form-data">

                        {{-- Basic Information --}}
                        <div class="mb-4">
                            <p class="text-muted small font-weight-bold text-uppercase mb-3 border-bottom">
                                Basic Information
                            </p>
                            <div class="form-row">

                                {{-- Category --}}
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold" style="color: #475569;">Category</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light" style="border-radius: 10px 0 0 10px;">
                                                <i class="fas fa-tag text-muted"></i>
                                            </span>
                                        </div>
                                        <select id="categorySelect" name="category" class="form-control bg-light select2"
                                            style="border-radius: 0 10px 10px 0;">
                                            <option value="">Select Category</option>
                                            <option value="1">Beverages</option>
                                            <option value="2">Dairy</option>
                                            <option value="3">Frozen Goods</option>
                                            <option value="4">Snacks</option>
                                            <option value="5">Condiments</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Product Name --}}
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold" style="color: #475569;">Product</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light" style="border-radius: 10px 0 0 10px;">
                                                <i class="fas fa-tag text-muted"></i>
                                            </span>
                                        </div>
                                        <select id="productSelect" name="product" class="form-control bg-light select2"
                                            style="border-radius: 0 10px 10px 0;">
                                            <option value="">Select Product</option>
                                            <option value="1">Beer</option>
                                            <option value="2">Surf</option>
                                            <option value="3">Downy</option>
                                            <option value="4">Coca-Cola</option>
                                            <option value="5">Love</option>
                                        </select>
                                    </div>
                                </div>


                            </div>
                        </div>

                        {{-- Price --}}
                        <p class="text-muted small font-weight-bold text-uppercase mb-3 border-bottom pb-1">
                            Price & Quantity.
                        </p>
                        <div class="form-row mb-4">

                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Cost Price</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">#</span>
                                    </div>
                                    <input type="number" name="quantity" id="quantityInput" class="form-control"
                                        step="0.01" placeholder="0.00" required>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Selling Price</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">#</span>
                                    </div>
                                    <input type="number" name="pack_size" id="packSizeInput" class="form-control"
                                        step="0.01" placeholder="0.00" required>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Available Qty.</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">#</span>
                                    </div>
                                    <input type="number" name="pack_size" id="packSizeInput" class="form-control"
                                        step="0.01" placeholder="0.00" required>
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



@endsection

@section('JS src')

@endsection
