@extends('themes.main')

{{-- 1. DEFINE PAGE TITLE --}}
@section('title', 'Product Management')


{{-- 2. DEFINE CONTENT HEADER (Breadcrumbs) --}}
@section('content')

    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero-header.css') }}">



    <div class="inv-hero">
        <div class="inv-hero-text">
            <h2><i class="fas fa-file-invoice mr-2"></i>Product Management</h2>
            <p>List of the products and information</p>
        </div>
        <div class="inv-hero-icon">
            <i class="fas fa-box"></i>
        </div>
    </div>

    <div class="main-card">
        <div class="table-card-header">
            <div>
                <h3 class="table-card-title">Product List</h3>
                <p class="table-card-subtitle">Product catalog grouped by category, type, and pack size.</p>
            </div>
            <button type="button" class="btn btn-primary table-card-action" data-toggle="modal"
                data-target="#registerProductModal">
                <i class="fas fa-plus"></i> Add Product
            </button>
        </div>
        <div class="table-responsive">
            <table id="example2" class="table table-modern">
                <thead>
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
                            <td><span class="record-id">PRDCT-{{ $prod->id }}</span></td>
                            <td class="font-weight-bold text-dark">{{ $prod->product_name }}</td>
                            <td>{{ $prod->category_name }}</td>
                            <td>
                                <span
                                    class="status-badge {{ strtolower($prod->perishable_type) === 'perishable' ? 'status-low-stock' : 'status-active' }}">
                                    {{ $prod->perishable_type }}
                                </span>
                            </td>
                            <td>{{ $prod->bundle_quantity }}</td>
                            <td>{{ $prod->bundle_size }}</td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="button" class="action-btn btn-edit mx-1" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="action-btn btn-delete mx-1" title="Delete">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- REGETRATION PRODUCT MODAL  --}}
    <div class="modal fade" id="registerProductModal" tabindex="-1" role="dialog"
        aria-labelledby="registerProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content shadow-lg modern-form-modal">

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
                            <p class="form-section-title">
                                Basic Information
                            </p>
                            <div class="form-row">

                                {{-- Category --}}
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold" style="color: #475569;">Category</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text h-100">
                                                <i class="fas fa-tag text-muted"></i>
                                            </span>
                                        </div>
                                        <select id="categorySelect" name="category" class="form-control bg-light select2"
                                            style="width: 1% !important; flex: 1 1 auto;">
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
                                            <span class="input-group-text h-100">
                                                <i class="fas fa-box text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="productName" id="productNameInput"
                                            class="form-control bg-light shadow-none" placeholder="Enter product name..."
                                            required>
                                    </div>
                                </div>

                                {{-- Perishable Type --}}
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold" style="color: #475569;">Perishable Type</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text h-100">
                                                <i class="fas fa-tag text-muted"></i>
                                            </span>
                                        </div>
                                        <select id="perishableTypeSelect" name="perishableType"
                                            class="form-control bg-light" required>
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
                        <p class="form-section-title">
                            Bundle Size
                        </p>
                        <div class="form-row mb-4">

                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Bundle Qty <br> <span class="font-weight-normal"
                                        style="font-size: 0.9em;">e.g
                                        Case/Sack</span> </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">#</span>
                                    </div>
                                    <input type="number" name="quantity" id="quantityInput" class="form-control"
                                        step="0.01" placeholder="0.00" required>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Bundle Size <br> <span class="font-weight-normal"
                                        style="font-size: 0.9em;">e.g
                                        Pcs/Bottle</span> </label>
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
