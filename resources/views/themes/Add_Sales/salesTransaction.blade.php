@extends('themes.main')

@section('title', 'Daily Sales')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/hero-header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/salesTransaction.css') }}">

    {{-- Blue Hero Header --}}
    <div class="inv-hero">
        <div class="inv-hero-text">
            <h2 class="font-weight-bold">Daily Sales</h2>
            <p>Record your daily sales</p>
        </div>
        <div class="inv-hero-icon">
            <i class="bi bi-person-fill"></i>
        </div>
    </div>

    <div class="row">

        {{-- Main Form --}}
        <div class="col-md-9">
            <div class="ds-form-panel">
                @include('layout.partials.alerts')

                <form id="dailySalesForm" action="#" method="POST">
                    @csrf

                    {{-- Sale Date --}}
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="ds-label">Sale Date <span class="req">*</span></label>
                            <input type="date" name="sale_date" id="sale_date" class="form-control ds-input"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="ds-label">Branch <span class="req">*</span></label>
                            <input type="text" class="form-control ds-input bg-light" value="Main Branch" readonly>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="ds-label">Prepared By <span class="req">*</span></label>
                            <input type="text" class="form-control ds-input bg-light" value="Admin" readonly>
                        </div>
                    </div>

                    <hr class="ds-divider">

                    {{-- Items Header --}}
                    <div class="ds-items-hd">
                        <h5 class="ds-items-title">
                            <i class="fas fa-box mr-1"></i> Sales Items
                        </h5>
                        <button type="button" id="addSaleRow" class="btn-add-item">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>

                    {{-- Items Table --}}
                    <div class="table-responsive">
                        <table class="table ds-table" id="salesTable">
                            <thead>
                                <tr>
                                    <th style="width: 40%;">PRODUCT</th>
                                    <th style="width: 18%;">QUANTITY SOLD</th>
                                    <th style="width: 22%;">TOTAL AMOUNT</th>
                                    <th style="width: 10%;">ROW TOTAL</th>
                                    <th style="width: 10%;"></th>
                                </tr>
                            </thead>
                            <tbody id="saleRows">
                                <tr class="sale-row">
                                    <td>
                                        <select name="inventory_id[]" class="form-control sale-product select2-product"
                                            style="width: 100%;">
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="quantity_sold[]" class="form-control sale-quantity"
                                            placeholder="0" min="1">
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">₱</span>
                                            </div>
                                            <input type="number" name="total_amount[]" class="form-control sale-amount"
                                                step="0.01" placeholder="0.00">
                                        </div>
                                    </td>
                                    <td class="sale-row-total font-weight-bold text-primary">₱0.00</td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-outline-danger btn-sm border-0 remove-sale-row">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Summary --}}
                    <div class="row justify-content-end mt-3">
                        <div class="col-md-4">
                            <div class="ds-summary">
                                <div class="ds-summary-row">
                                    <span class="ds-summary-label">Total Items</span>
                                    <span id="total_items" class="font-weight-bold">0</span>
                                </div>
                                <div class="ds-summary-row">
                                    <span class="ds-summary-label">Total Quantity</span>
                                    <span id="total_quantity" class="font-weight-bold">0</span>
                                </div>
                                <div class="ds-total-divider"></div>
                                <div class="ds-summary-row">
                                    <span class="ds-summary-label font-weight-bold">Grand Total</span>
                                    <span id="grand_total" class="ds-summary-value">₱0.00</span>
                                </div>
                                <input type="hidden" name="grand_total_raw" id="grand_total_raw">
                            </div>
                        </div>
                    </div>

                    {{-- Save Button --}}
                    <div class="mt-4">
                        <button type="submit" class="btn-add-item px-5">
                            <i class="fas fa-save mr-1"></i> Save Sales
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- RECENT ADDED SUPPLY --}}
        <div class="col-md-3">
            <div class="card elevation-2 card-recent-added">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">Recently Transaction</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>

                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <ul class="list-unstyled mb-0">
                        @foreach ($query->take(5) as $item)
                            <li class="d-flex align-items-center justify-content-between px-3 py-2"
                                style="border-bottom: 0.5px solid rgba(0,0,0,0.07);">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3 d-flex align-items-center justify-content-center"
                                        style="width:34px; height:34px; border-radius:9px; background:#c8d7ff; font-size:16px;">
                                        <i class="bi bi-box2-fill text-primary"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:13.5px; font-weight:600;">{{ $item->sale_date }}</div>
                                        <div class="text-muted" style="font-size:11px;"></div>
                                    </div>
                                </div>
                                <span class="badge"
                                    style="background:#fff3cd; color:#856404; font-size:12px; font-weight:700; padding:4px 10px; border-radius:20px;">
                                    ₱{{ number_format($item->total_amount, 2) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="card-footer text-center">
                        <a href="{{ route('inventory') }}" class="uppercase text-primary">Check Remaining Quantity</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('JS src')
    <script src="{{ asset('js/dailyTransaction.js') }}"></script>
    <script>
        $(document).ready(function() {
            initDailySales({
                getProductsUrl: "{{ route('get_products') }}",
                saveDailySalesUrl: "{{ route('save_daily_sales') }}",
            });
        });
    </script>
@endsection
