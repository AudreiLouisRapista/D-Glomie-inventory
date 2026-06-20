@extends('themes.main')

@section('title', 'Stock Transfer')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/hero-header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/salesTransaction.css') }}">

    {{-- Hero Header --}}
    <div class="inv-hero">
        <div class="inv-hero-text">
            <h1 class="font-weight-bold">Stock Transfer</h1>
            <p>Transfer stock to another branch</p>
        </div>
        <div class="inv-hero-icon">
            <i class="bi bi-arrow-left-right"></i>
        </div>
    </div>

    @include('layout.partials.alerts')

    <form id="stockTransferForm" action="{{ route('save_stock_transfer') }}" method="POST">
        @csrf

        <div class="row">
            {{-- LEFT SIDE: Information & Items Selection (8 Columns) --}}
            <div class="col-lg-8">
                <div class="ds-form-panel mb-4">
                    {{-- Transfer Info --}}
                    <p class="form-section-title">Transfer Information</p>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="ds-label">Transfer Date <span class="req">*</span></label>
                            <input type="date" name="transfer_date" id="transfer_date" class="form-control ds-input"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="ds-label">To Branch <span class="req">*</span></label>
                            <select name="to_branch_id" id="to_branch_id" class="form-control ds-input" required>
                                <option value="">-- Select Branch --</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="ds-label">From Branch</label>
                            <input type="text" class="form-control ds-input bg-light"
                                value="{{ session('branch_name') }}" readonly>
                            <input type="hidden" name="from_branch_id" value="{{ session('branch_id') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="ds-label">Prepared By</label>
                            <input type="text" class="form-control ds-input bg-light" value="{{ session('user_role') }}"
                                readonly>
                        </div>
                    </div>

                    <hr class="ds-divider">

                    {{-- Transfer Items Header --}}
                    <div class="ds-items-hd mb-3">
                        <h5 class="ds-items-title">
                            <i class="bi bi-box-arrow-right mr-1"></i> Transfer Items
                        </h5>
                    </div>

                    {{-- Items Table --}}
                    <div class="table-responsive">
                        <table class="table ds-table mb-2" id="transferTable">
                            <thead>
                                <tr>
                                    <th style="width: 40%;">PRODUCT</th>
                                    <th style="width: 15%;">QTY</th>
                                    <th style="width: 20%;">UNIT PRICE</th>
                                    <th style="width: 20%;">TOTAL</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody id="transferRows">
                                <tr class="transfer-row">
                                    <td>
                                        <select name="inventory_id[]" class="form-control transfer-product select2-transfer"
                                            style="width: 100%;">
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="quantity[]" class="form-control transfer-quantity"
                                            placeholder="0" min="1">
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">₱</span>
                                            </div>
                                            <input type="number" name="unit_price[]"
                                                class="form-control transfer-unit-price bg-light" step="0.01"
                                                placeholder="0.00" readonly>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">₱</span>
                                            </div>
                                            <input type="number" name="amount[]"
                                                class="form-control transfer-amount bg-light" step="0.01"
                                                placeholder="0.00" readonly>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-outline-danger btn-sm border-0 remove-transfer-row">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- ADD ITEM BUTTON (Repositioned below the dropdown line) --}}
                    <div class="mt-2">
                        <button type="button" id="addTransferRow" class="btn btn-outline-primary btn-sm"><i
                                class="fas fa-plus mr-1"></i> Add Item</button>
                    </div>

                </div>
            </div>

            {{-- RIGHT SIDE: Summary Sticky Sidebar (4 Columns) --}}
            <div class="col-lg-4">
                <div class="ds-form-panel position-sticky" style="top: 20px;">
                    <p class="form-section-title">Transfer Summary</p>

                    <div class="ds-summary mt-3">
                        <div class="ds-summary-row d-flex justify-content-between mb-2">
                            <span class="ds-summary-label text-muted">Total Items</span>
                            <span id="total_items" class="font-weight-bold">0</span>
                        </div>
                        <div class="ds-summary-row d-flex justify-content-between mb-3">
                            <span class="ds-summary-label text-muted">Total Quantity</span>
                            <span id="total_quantity" class="font-weight-bold">0</span>
                        </div>

                        <div class="ds-total-divider my-3" style="border-top: 1px dashed #dee2e6;"></div>

                        <div class="ds-summary-row d-flex justify-content-between align-items-center mb-4">
                            <span class="ds-summary-label font-weight-bold h5 mb-0">Grand Total</span>
                            <span id="grand_total"
                                class="ds-summary-value text-primary font-weight-bold h4 mb-0">₱0.00</span>
                        </div>
                        <input type="hidden" name="grand_total_raw" id="grand_total_raw">
                    </div>

                    {{-- Action Button --}}
                    <div class="mt-4">
                        <button type="submit" class="btn-add-item btn-block w-100 py-2">
                            <i class="fas fa-save mr-1"></i> Save Transfer
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>

@endsection

@section('JS src')
    <script src="{{ asset('js/stockOut.js') }}"></script>
    <script>
        $(document).ready(function() {
            initStockTransfer({
                getProductsUrl: "{{ route('get_products_stockOut') }}",
                saveStockTransferUrl: "{{ route('save_stock_transfer') }}",
            });
        });
    </script>
@endsection
