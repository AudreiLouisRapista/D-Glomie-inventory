@extends('themes.main')

{{-- 1. DEFINE PAGE TITLE --}}
@section('title', 'User Profile')

{{-- 2. DEFINE CONTENT HEADER (Breadcrumbs) --}}
@section('content')

    <link rel="stylesheet" href="{{ asset('css/product.css') }}">

    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold">Product Management</h1>
        <p>Manage Product and their Details</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Product List</h3>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#registerProductModal"
                style="float: right;">
                Add Product
            </button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example2" class="table table-bordered table-hover text-center">
                <thead style="text-align: center; background-color: #f8fafc;">
                    <tr>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Perishable Type</th>
                        <th>Quantity</th>
                        <th>Pack Size</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $prod)
                        <tr>
                            <td>PRDCT-{{ $prod->id }}</td>
                            <td>{{ $prod->product_name }}</td>
                            <td>{{ $prod->category_name }}</td>
                            <td>{{ $prod->perishable_type }}</td>
                            <td>{{ $prod->product_quantity }}</td>
                            <td>{{ $prod->product_size }}</td>
                            <td><button type="button" class="btn btn-success"><i class="bi bi-pencil-square"></i></button>
                                <button type="button" class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                            </td>
                        </tr>
                    @endforeach
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

                @include('layout.partials.alerts')

                {{-- Modal Header --}}
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title font-weight-bold" id="registerProductModalLabel">
                        <i class="fas fa-box-open mr-2"></i> Register New Product
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body p-4">
                    <form id="registerProductForm" action="{{ route('save_product') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
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

                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Product Name --}}
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold" style="color: #475569;">Product</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light" style="border-radius: 10px 0 0 10px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="productName" id="productNameInput"
                                            class="form-control bg-light shadow-none" placeholder="Enter product name..."
                                            style="border-radius: 0 10px 10px 0; height: 45px;" required>
                                    </div>
                                </div>

                                {{-- Perishable Type --}}
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold" style="color: #475569;">Perishable Type</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light" style="border-radius: 10px 0 0 10px;">
                                                <i class="fas fa-tag text-muted"></i>
                                            </span>
                                        </div>
                                        <select id="perishableTypeSelect" name="perishableType"
                                            class="form-control bg-light"
                                            style="border-radius: 0 10px 10px 0; height: 45px;" required>
                                            <option value="">Select Perishable Type</option>
                                            @foreach ($perishables as $perish)
                                                <option value="{{ $perish->id }}">{{ $perish->perishable_type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Bundle Size --}}
                        <p class="text-muted small font-weight-bold text-uppercase mb-3 border-bottom pb-1">
                            Bundle Size
                        </p>
                        <div class="form-row mb-4">

                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Quantity</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">#</span>
                                    </div>
                                    <input type="number" name="quantity" id="quantityInput" class="form-control"
                                        step="0.01" placeholder="0.00" required>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Pack Size</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">#</span>
                                    </div>
                                    <input type="number" name="packSize" id="packSizeInput" class="form-control"
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
