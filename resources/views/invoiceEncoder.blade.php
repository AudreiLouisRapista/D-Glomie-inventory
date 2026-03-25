@extends('themes.main')

{{-- 1. DEFINE PAGE TITLE --}}
@section('title', 'Invoice Encoder')

{{-- 2. DEFINE CONTENT --}}
@section('content')

    <link rel="stylesheet" href="{{ asset('css/invoiceEncoder.css') }}">

    <div class="col-sm-6">
        <h1></h1>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="inv-hero">
                <div class="inv-hero-text">
                    <h2> Invoice Management</h2>
                    <p>Create and manage your invoices</p>
                </div>
                <div class="inv-hero-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
            </div>
        </div>{{-- end .col-12 --}}

        {{-- White Form Panel --}}
        <div class="col-md-9">
            <div class="form-panel elevation-2">
                @include('layout.partials.alerts')
                {{-- @if ($errors->any())
                    {{-- <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}
                <form id="invoiceEncoderForm" action="{{ route('save_invoiceDetails') }}" method="POST">
                    @csrf
                    {{-- Row 1: Invoice Number + Supplier --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="inv-label">Invoice Number <span class="req">*</span></label>
                            <input type="text" id="invoice_number" name="invoiceNumber" class="form-control inv-input"
                                placeholder="INV-001">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="inv-label">Supplier Name <span class="req">*</span></label>
                            <select id="supplier_select" name="supplierId" class="form-control inv-input">
                                <option value="">Choose a supplier...</option>
                                @foreach ($supplier as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Row 2: Date + Due Date --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="inv-label">Date <span class="req">*</span></label>
                            <input type="date" id="invoice_date" name="invoiceDate" class="form-control inv-input"
                                value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="inv-label">Due Date <span class="req">*</span></label>
                            <input type="date" id="due_date" name="invoiceduoDate" class="form-control inv-input"
                                value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <hr class="inv-divider">

                    {{-- Invoice Items Header --}}
                    <div class="items-hd">
                        <h5 class="items-title">Invoice Items</h5>
                        <button type="button" id="addRow" class="btn btn-add-item">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>

                    {{-- Items Table --}}
                    <div class="table-responsive">
                        <table class="table inv-table" id="inv-table">
                            <thead class="table-light">
                                <tr class="small text-uppercase text-muted">
                                    <th style="width: 9%;">QTY</th>
                                    <th style="width: 22%;">DESCRIPTION (PRODUCT)</th>
                                    <th style="width: 6%;">QUANTITY</th>
                                    <th style="width: 6%;">PACK SIZE</th>
                                    <th style="width: 15%;">TYPE</th>
                                    <th class="expiry-column" style="width: 12%; display: none;">EXPIRY DATE</th>
                                    <th style="width: 10%;">UNIT PRICE</th>
                                    <th style="width: 8%;">PRICE</th>
                                    <th style="width: 10%;">AMOUNT</th>
                                    <th style="width: 6%;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemRows">
                                <tr class="item-row">
                                    <td><input type="number" name="CSquantity[]" class="form-control CSquantity"
                                            value="1">
                                    </td>
                                    <td><input type="text" name="productName[]" list="productData"
                                            class="form-control productName" required></td>
                                    <td><input type="number" name="Quantinumber[]" class="form-control Quantinumber"
                                            value="0" readonly></td>
                                    <td><input type="number" name="productSize[]" class="form-control productSize"
                                            value="1" readonly></td>
                                    <td>
                                        <input type="text" name="perishableType[]"
                                            class="form-control perishableType bg-light" readonly placeholder="-">
                                    </td>
                                    <td class="expiry-column" style="display: none;">
                                        <div class="expiry-wrapper" style="display: none;">
                                            <input type="date" name="expdate[]" class="form-control expdate">
                                        </div>
                                    </td>
                                    <td><input type="number" name="unitPrice[]" class="form-control unitPrice"
                                            step="0.01">
                                    </td>
                                    <td class="totalPrice fw-bold">0.00</td>
                                    <td class="row-total fw-bold text-primary">0.00</td>
                                    <td>
                                        <button type="button" class="btn btn-outline-danger btn-sm border-0 remove-row">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <datalist id="productData">
                            {{-- Dynamically populated by JS to show only top 10 --}}
                        </datalist>
                    </div>

                    {{-- Summary --}}
                    <div class="row justify-content-end">
                        <div class="col-md-4 p-4 bg-light rounded-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Gross Amount:</span>
                                <span id="gross_total" class="fw-bold text-dark">₱0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Vatable Sales:</span>
                                <span id="vatable_sales" class="fw-bold text-dark">₱0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">VAT (12%):</span>
                                <span id="vat_amount" class="fw-bold text-dark">₱0.00</span>
                            </div>
                            <div
                                class="d-flex justify-content-between align-items-center pt-3 border-top border-secondary border-opacity-10">
                                <span class="h6 fw-bold mb-0">Grand Total (Net):</span>
                                <span id="grand_total" class="h4 fw-bold text-primary mb-0">₱0.00</span>
                            </div>
                            <input type="hidden" name="gross_total_raw" id="gross_total_raw">
                            <input type="hidden" name="vat_amount_raw" id="vat_amount_raw">
                            <input type="hidden" name="grand_total_raw" id="grand_total_raw">
                        </div>
                    </div>

                    {{-- Save Button --}}

                    <button type="submit" class="btn btn-add-item">
                        <i class="fas fa-save"></i> Save Invoice
                    </button>

                </form>
            </div>{{-- end .form-panel --}}
        </div>


        {{-- RECENT ADDED SUPPLY --}}
        <div class="col-md-3">
            <div class="card elevation-2 card-recent-added">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">Recently Added Supply</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>

                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0 ">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        <li class="item">
                            <div class="product-img">
                                <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                            </div>
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">Samsung TV
                                    <span class="badge badge-warning float-right">$1800</span></a>
                                <span class="product-description">
                                    Samsung 32" 1080p 60Hz LED Smart HDTV.
                                </span>
                            </div>
                        </li>
                        <!-- /.item -->
                        <li class="item">
                            <div class="product-img">
                                <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                            </div>
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">Bicycle
                                    <span class="badge badge-info float-right">$700</span></a>
                                <span class="product-description">
                                    26" Mongoose Dolomite Men's 7-speed, Navy Blue.
                                </span>
                            </div>
                        </li>
                        <!-- /.item -->
                        <li class="item">
                            <div class="product-img">
                                <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                            </div>
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">
                                    Xbox One <span class="badge badge-danger float-right">
                                        $350
                                    </span>
                                </a>
                                <span class="product-description">
                                    Xbox One Console Bundle with Halo Master Chief Collection.
                                </span>
                            </div>
                        </li>
                        <!-- /.item -->
                        <li class="item">
                            <div class="product-img">
                                <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                            </div>
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">PlayStation 4
                                    <span class="badge badge-success float-right">$399</span></a>
                                <span class="product-description">
                                    PlayStation 4 500GB Console (PS4)
                                </span>
                            </div>
                        </li>
                        <!-- /.item -->
                    </ul>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center">
                    <a href="javascript:void(0)" class="uppercase">View All Products</a>
                </div>
                <!-- /.card-footer -->
            </div>
        </div>
    </div>



@endsection


@section('JS src')
    <script src="{{ asset('js/invoiceEncoder.js') }}"></script>
    <script>
        const allProducts = @json($products);

        $(document).ready(function() {
            initInvoiceEncoder(allProducts);
        });
    </script>
@endsection
