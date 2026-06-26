@extends('themes.main')

{{-- 1. DEFINE PAGE TITLE --}}
@section('title', 'Invoice Encoder')

{{-- 2. DEFINE CONTENT --}}
@section('content')

    <link rel="stylesheet" href="{{ asset('css/hero-header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/salesTransaction.css') }}">

    {{-- Hero Header --}}
    <div class="inv-hero">
        <div class="inv-hero-text">
            <h1 class="font-weight-bold">Stock In</h1>
            <p>Record your new supply</p>
        </div>
        <div class="inv-hero-icon">
            <i class="bi bi-receipt"></i>
        </div>
    </div>

    @include('layout.partials.alerts')

    <form id="invoiceEncoderForm" action="{{ route('save_invoiceDetails') }}" method="POST">
        @csrf

        <div class="row">
            {{-- LEFT SIDE: Main Form Panel & Line Items Selection (8 Columns) --}}
            <div class="col-lg-9">
                <div class="ds-form-panel mb-4">

                    {{-- Invoice Info Section --}}
                    <p class="form-section-title">Invoice Information</p>

                    {{-- Row 1: Invoice Number + Supplier --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="ds-label">Invoice Number <span class="req">*</span></label>
                            <input type="text" id="invoice_number" name="invoiceNumber" class="form-control ds-input"
                                placeholder="INV-001"
                                oninput="if(this.value.length > 11) this.value = this.value.slice(0, 12);" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="ds-label">Supplier Name <span class="req">*</span></label>
                            <select id="supplier_select" name="supplierId" class="form-control ds-input select2-supplier"
                                style="width: 100%;" required>
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
                            <label class="ds-label">Date <span class="req">*</span></label>
                            <input type="date" id="invoice_date" name="invoiceDate" class="form-control ds-input"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="ds-label">Due Date <span class="req">*</span></label>
                            <input type="date" id="due_date" name="invoiceduoDate" class="form-control ds-input"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <hr class="ds-divider">

                    {{-- Invoice Items Header Section --}}
                    <div class="ds-items-hd mb-3">
                        <h5 class="ds-items-title">
                            <i class="bi bi-box-seam mr-1"></i> Invoice Items
                        </h5>
                    </div>

                    {{-- Items Table --}}
                    <div class="table-responsive">
                        <table class="table ds-table mb-2" id="inv-table">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">QTY</th>
                                    <th style="width: 30%;">DESCRIPTION (PRODUCT)</th>
                                    <th style="width: 10%;">BNDL QTY</th>
                                    <th style="width: 10%;">BNDL SIZE</th>
                                    <th style="width: 15%;">TYPE</th>
                                    <th class="expiry-column" style="width: 15%; display: none;">EXPIRY DATE</th>
                                    <th style="width: 15%;">UNIT PRICE</th>
                                    <th style="width: 10%;">PRICE</th>
                                    <th style="width: 15%;">AMOUNT</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemRows">
                                <tr class="item-row">
                                    <td>
                                        <input type="number" name="CSquantity[]" class="form-control ds-input CSquantity"
                                            value="1" min="1">
                                    </td>
                                    <td>
                                        <select name="productId[]" class="form-control ds-input productName select2-product"
                                            style="width: 100%;">
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="Quantinumber[]"
                                            class="form-control ds-input Quantinumber bg-light" value="0" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="productSize[]"
                                            class="form-control ds-input productSize bg-light" value="1" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="perishableType[]"
                                            class="form-control ds-input perishableType bg-light" readonly placeholder="-">
                                    </td>
                                    <td class="expiry-column" style="display: none;">
                                        <div class="expiry-wrapper" style="display: none;">
                                            <input type="date" name="expdate[]" class="form-control ds-input expdate">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="unitPrice[]" class="form-control ds-input unitPrice"
                                            step="0.01" placeholder="0.00">
                                    </td>
                                    <td class="totalPrice font-weight-bold align-middle">0.00</td>
                                    <td class="row-total font-weight-bold text-primary align-middle">0.00</td>
                                    <td>
                                        <button type="button" class="btn btn-outline-danger btn-sm border-0 remove-row">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Dynamic Interaction Controls --}}
                    <div class="mt-2">
                        <button type="button" id="addRow" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Add Item
                        </button>
                    </div>

                </div>
            </div>

            {{-- RIGHT SIDE: Tightened, Compact Invoice Summary Sidebar (4 Columns) --}}
            <div class="col-lg-3">
                <div class="position-sticky" style="top: 20px;">

                    {{-- Adjusted padding (py-3 px-3) and margin bottom (mb-3) for a slimmer footprint --}}
                    <div class="ds-form-panel py-3 px-3 mb-3">
                        <p class="form-section-title mb-2" style="font-size: 0.9rem; letter-spacing: 0.5px;">Invoice
                            Summary</p>

                        {{-- Added small class for streamlined typography --}}
                        <div class="ds-summary small">
                            <div class="ds-summary-row d-flex justify-content-between mb-1.5">
                                <span class="ds-summary-label text-muted">Gross Amount</span>
                                <span id="gross_total" class="font-weight-bold text-dark">₱0.00</span>
                            </div>
                            <div class="ds-summary-row d-flex justify-content-between mb-1.5">
                                <span class="ds-summary-label text-muted">Vatable Sales</span>
                                <span id="vatable_sales" class="font-weight-bold text-dark">₱0.00</span>
                            </div>
                            <div class="ds-summary-row d-flex justify-content-between mb-2">
                                <span class="ds-summary-label text-muted">VAT (12%)</span>
                                <span id="vat_amount" class="font-weight-bold text-dark">₱0.00</span>
                            </div>

                            <div class="ds-total-divider my-2 style-dashed" style="border-top: 1px dashed #dee2e6;"></div>

                            {{-- Kept readable but cleaned up the sizing parameters --}}
                            <div class="ds-summary-row d-flex justify-content-between align-items-center my-2">
                                <span class="ds-summary-label font-weight-bold text-dark"
                                    style="font-size: 0.85rem;">Grand Total (Net)</span>
                                <span id="grand_total"
                                    class="ds-summary-value text-primary font-weight-bold h5 mb-0">₱0.00</span>
                            </div>

                            <input type="hidden" name="gross_total_raw" id="gross_total_raw">
                            <input type="hidden" name="vat_amount_raw" id="vat_amount_raw">
                            <input type="hidden" name="grand_total_raw" id="grand_total_raw">
                        </div>

                        {{-- Action button matches the streamlined height profile --}}
                        <div class="mt-3">
                            <button type="submit" class="btn-add-item btn-block w-100 py-2 btn-sm font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Save Invoice
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </form>

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
