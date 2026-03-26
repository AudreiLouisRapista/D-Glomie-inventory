@extends('themes.main')

{{-- 1. DEFINE PAGE TITLE --}}
@section('title', 'Supplier Payment Tracking')

{{-- 3. DEFINE MAIN CONTENT --}}
@section('content')
    <link rel="stylesheet" href="{{ asset('css/paymentTracker.css') }}">
    <div class="col-sm-6">
        <h1></h1>
    </div>
    {{-- ── Hidden Print Receipt Template (never shown on screen) ── --}}
    <div id="print-receipt-area" style="display:none;">
        <div style="width:100%; font-family:'DM Sans',sans-serif; background:#fff;">

            {{-- Header --}}
            <div
                style="background:#28a745; padding:28px 36px; display:flex; justify-content:space-between; align-items:center; -webkit-print-color-adjust:exact; print-color-adjust:exact;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div
                        style="width:40px; height:40px; background:rgba(255,255,255,.2); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#fff"
                            viewBox="0 0 16 16">
                            <path
                                d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z" />
                            <path
                                d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z" />
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:17px; font-weight:700; color:#fff; letter-spacing:-.3px;">Payment Receipt
                        </div>
                        <div style="font-size:11px; color:rgba(255,255,255,.75); margin-top:2px;">Official Payment
                            Confirmation</div>
                    </div>
                </div>
                <div
                    style="background:rgba(255,255,255,.2); border:1.5px solid rgba(255,255,255,.4); color:#fff; padding:5px 12px; border-radius:20px; font-size:11px; font-weight:700; letter-spacing:.5px;">
                    ✓ SETTLED</div>
            </div>

            {{-- Body --}}
            <div style="padding:28px 36px;">

                {{-- Invoice box --}}
                <div
                    style="background:#f8f9fa; border:1px solid #e9ecef; border-radius:10px; padding:14px 18px; display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; -webkit-print-color-adjust:exact; print-color-adjust:exact;">
                    <div>
                        <div
                            style="font-size:9px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#6c757d; margin-bottom:4px;">
                            Invoice Number</div>
                        <div id="print-invoice-no"
                            style="font-family:'DM Mono',monospace; font-size:15px; font-weight:600; color:#1a1a2e;">—</div>
                    </div>
                    <div style="text-align:right;">
                        <div
                            style="font-size:9px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#6c757d; margin-bottom:4px;">
                            Total Settled</div>
                        <div id="print-total-paid" style="font-size:20px; font-weight:700; color:#28a745;">₱ 0.00</div>
                    </div>
                </div>

                {{-- Table --}}
                <div
                    style="font-size:9px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#6c757d; margin-bottom:10px;">
                    Payment Details</div>
                <table style="width:100%; border-collapse:collapse; font-size:12.5px;">
                    <thead>
                        <tr style="border-bottom:2px solid #dee2e6;">
                            <th
                                style="font-size:9px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#6c757d; padding:0 8px 8px 0; text-align:left;">
                                Date</th>
                            <th
                                style="font-size:9px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#6c757d; padding:0 8px 8px 0; text-align:left;">
                                Ref No.</th>
                            <th
                                style="font-size:9px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#6c757d; padding:0 8px 8px 0; text-align:left;">
                                Method</th>
                            <th
                                style="font-size:9px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#6c757d; padding:0 0 8px 0; text-align:right;">
                                Amount</th>
                        </tr>
                    </thead>
                    <tbody id="print-payment-rows">
                        {{-- Populated by JS --}}
                    </tbody>
                </table>

                {{-- Total row --}}
                <div
                    style="display:flex; justify-content:space-between; align-items:center; background:#e8f5ec; border:1px solid rgba(40,167,69,.2); border-radius:8px; padding:12px 18px; margin-top:16px; -webkit-print-color-adjust:exact; print-color-adjust:exact;">
                    <span style="font-size:12px; font-weight:600; color:#1a1a2e;">Total Amount Paid</span>
                    <span id="print-total-paid-2" style="font-size:17px; font-weight:700; color:#28a745;">₱ 0.00</span>
                </div>
            </div>

            {{-- Footer --}}
            <div style="padding:18px 36px 28px; border-top:1px dashed #dee2e6; text-align:center;">
                <div style="font-size:12px; font-weight:600; color:#1a1a2e; margin-bottom:3px;">Thank you for your payment!
                </div>
                <div style="font-size:11px; color:#6c757d; line-height:1.6;">
                    This is an official receipt. Please keep this for your records.<br>
                    Printed on: <strong id="print-date"></strong>
                </div>
            </div>

        </div>
    </div>
    {{-- ── End Print Receipt Area ── --}}


    {{-- Blue Hero Header --}}
    <div class="inv-hero">
        <div class="inv-hero-text">
            <h2><i class="fas fa-file-invoice mr-2"></i>Payment Tracker</h2>
            <p>Track the balance of every invoices</p>
        </div>
        <div class="inv-hero-icon">
            <i class="bi bi-credit-card-fill"></i>
        </div>
    </div>
    <div class="container-fluid px-4">
        <div class="main-card">
            <div class="filters">
                <div class="filter-group">
                    <label for="filterSupplier">Supplier Name</label>
                    <select id="filterSupplier" class="form-select shadow-none">
                        <option value="">All Suppliers</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterStatus">Payment Status</label>
                    <select id="filterStatus" class="form-select shadow-none">
                        <option value="">All Statuses</option>
                        <option value="unpaid">Unpaid</option>
                        <option value="partial">Partial</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">

                <!-- Add this before the table to debug -->
                <table id="example2" class="table">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Supplier</th>
                            <th>Dates</th>
                            <th>Financials</th>
                            <th>Total Paid</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $row)
                            @php
                                $remaining = $row->invoice_netAmount - ($row->total_paid ?? 0);
                            @endphp
                            <tr>
                                <td class="fw-bold text-dark">INV-{{ $row->invoice_number }}</td>
                                <td>{{ $row->supplier_name }}</td>
                                <td>
                                    <div class="small">Inv:
                                        {{ \Carbon\Carbon::parse($row->invoice_date)->format('M d, Y') }}</div>
                                    <div class="small text-danger fw-bold">Due:
                                        {{ \Carbon\Carbon::parse($row->invoice_duo_date)->format('M d, Y') }}</div>
                                </td>
                                <td>
                                    <div class="small text-muted">Gross: {{ number_format($row->invoice_grossAmount, 2) }}
                                    </div>
                                    <div class="fw-bold">Net: ₱{{ number_format($row->invoice_netAmount, 2) }}</div>
                                </td>
                                <td class="text-success fw-bold">₱{{ number_format($row->total_paid ?? 0, 2) }}</td>
                                <td class="text-danger fw-bold">₱{{ number_format($remaining, 2) }}</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($row->invoice_status) }}">
                                        {{ $row->invoice_status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center">
                                        {{-- View Button --}}
                                        <button type="button" class="action-btn btn-view mx-1" data-toggle="modal"
                                            data-target="#viewItemsModal{{ $row->id }}">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </button>

                                        @if ($remaining > 0.01)
                                            {{-- Pay Button --}}
                                            <button type="button" class="action-btn btn-pay pay-btn mx-1"
                                                data-id="{{ $row->id }}" data-invoice="{{ $row->invoice_number }}"
                                                data-remaining="{{ $remaining }}"
                                                data-net="{{ $row->invoice_netAmount }}">
                                                <i class="fas fa-credit-card mr-1"></i> Pay
                                            </button>
                                        @else
                                            {{-- History Button --}}
                                            <button type="button" class="action-btn btn-viewPaymentHistory mx-1"
                                                data-toggle="modal" data-target="#paymentHistoryModal"
                                                data-invoice="{{ $row->invoice_number }}"
                                                data-id="{{ $row->purchase_id }}">
                                                <i class="fas fa-history mr-1"></i> History
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- Payment Modal --}}
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">

                {{-- Modal Header --}}
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title font-weight-bold" id="paymentModalLabel">
                        <i class="fas fa-credit-card mr-2 text-primary"></i> Record Payment
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('save_payment') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">

                        {{-- Summary Information Card --}}
                        <div class="p-3 mb-4 border-0 shadow-sm bg-light" style="border-radius: 12px;">
                            <p class="text-muted small font-weight-bold text-uppercase mb-2 border-bottom pb-1">
                                Invoice Details
                            </p>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Invoice Number:</span>
                                <span class="font-weight-bold text-dark" id="modalInvoiceNumber"></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Remaining Balance:</span>
                                <span class="font-weight-bold text-danger" id="modalRemainingBalance"></span>
                            </div>
                        </div>

                        <input type="hidden" name="purchase_id" id="modal_purchase_id">
                        <input type="hidden" name="old_remaining_balance" id="old_remaining_balance">

                        {{-- Form Fields --}}
                        <div class="row">
                            {{-- Payment Date --}}
                            <div class="col-12 mb-3">
                                <label class="font-weight-bold small" style="color: #475569;">Payment Date</label>
                                <input type="date" name="payment_date" id="paymentDate"
                                    class="form-control bg-light shadow-none border-0" value="{{ date('Y-m-d') }}"
                                    style="border-radius: 10px; height: 45px;" required>
                            </div>

                            {{-- Amount Paid --}}
                            <div class="col-12 mb-3">
                                <label class="font-weight-bold small" style="color: #475569;">Amount Paid</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-0 font-weight-bold"
                                            style="border-radius: 10px 0 0 10px;">₱</span>
                                    </div>
                                    <input type="number" name="amount_paid" id="amountPaid" step="0.01"
                                        min="0" class="form-control bg-light shadow-none border-0"
                                        placeholder="0.00" style="border-radius: 0 10px 10px 0; height: 45px;" required>
                                </div>
                            </div>

                            {{-- Payment Method --}}
                            <div class="col-12 mb-3">
                                <label class="font-weight-bold small" style="color: #475569;">Payment Method</label>
                                <select name="payment_method" id="paymentMethod"
                                    class="form-control bg-light shadow-none border-0"
                                    style="border-radius: 10px; height: 45px;" required>
                                    <option value="">-- Select Method --</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Check">Check</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Mobile Payment">Mobile Payment</option>
                                </select>
                            </div>

                            {{-- Reference Number --}}
                            <div class="col-12 mb-3">
                                <label class="font-weight-bold small" style="color: #475569;">Reference Number</label>
                                <input type="text" name="reference_number" id="referenceNumber"
                                    class="form-control bg-light shadow-none border-0"
                                    placeholder="e.g. Check #, Trans ID" style="border-radius: 10px; height: 45px;">
                            </div>
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-light px-4 mr-2" data-dismiss="modal"
                                style="border-radius: 8px;">Cancel</button>
                            <button type="submit" class="btn btn-primary px-5 font-weight-bold shadow-sm"
                                style="border-radius: 8px;">
                                Save Payment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- View Items Modals --}}
    @foreach ($purchases as $purchase)
        <div class="modal fade" id="viewItemsModal{{ $purchase->id }}" tabindex="-1" role="dialog"
            aria-labelledby="viewItemsModalLabel{{ $purchase->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">

                    {{-- Modal Header --}}
                    <div class="modal-header bg-dark text-white py-3">
                        <h5 class="modal-title font-weight-bold" id="viewItemsModalLabel{{ $purchase->id }}">
                            <i class="fas fa-file-invoice mr-2 text-info"></i>
                            Invoice: {{ $purchase->invoice_number }}
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="modal-body p-4">
                        {{-- Purchase Meta Information --}}
                        <div class="mb-4">
                            <p class="text-muted small font-weight-bold text-uppercase mb-3 border-bottom">
                                Purchase Details
                            </p>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold d-block" style="color: #475569;">Supplier</label>
                                    <div class="p-2 bg-light" style="border-radius: 10px; border: 1px solid #e2e8f0;">
                                        <span class="text-dark">{{ $purchase->supplier_name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold d-block" style="color: #475569;">Date Received</label>
                                    <div class="p-2 bg-light" style="border-radius: 10px; border: 1px solid #e2e8f0;">
                                        <span class="text-dark">{{ $purchase->invoice_date }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Items Table --}}
                        <div class="table-responsive" style="border-radius: 10px; border: 1px solid #e2e8f0;">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-uppercase small font-weight-bold">
                                    <tr>
                                        <th class="border-0">Qty (Pcs)</th>
                                        <th class="border-0">Description</th>
                                        <th class="border-0">U. Price</th>
                                        <th class="border-0">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($purchase_items[$purchase->id]) && count($purchase_items[$purchase->id]) > 0)
                                        @foreach ($purchase_items[$purchase->id] as $item)
                                            <tr>
                                                <td>{{ $item->tie_total }}</td>
                                                <td>{{ $item->product_name }}</td>
                                                <td>₱{{ number_format($item->unit_price, 2) }}</td>
                                                <td class="font-weight-bold">₱{{ number_format($item->total_price, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No items found for this
                                                invoice.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        {{-- Summary & Footer --}}
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <div>
                                <p class="text-muted small font-weight-bold text-uppercase mb-0">Net Amount</p>
                                <h3 class="font-weight-bold text-primary mb-0">
                                    ₱{{ number_format($purchase->invoice_netAmount, 2) }}</h3>
                            </div>
                            <div class="d-flex">
                                <button type="button" class="btn btn-light px-4 shadow-sm font-weight-bold"
                                    data-dismiss="modal" style="border-radius: 8px;">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

    {{-- Payment History Modal --}}
    <div class="modal fade" id="paymentHistoryModal" tabindex="-1" aria-labelledby="paymentHistoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" id="paymentHistoryModalLabel">
                        <i class="fas fa-receipt text-success me-2"></i> Payment History
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <div class="d-flex justify-content-between align-items-center p-3 mb-4 rounded-3"
                        style="background-color: #f8f9fa; border: 1px solid #e9ecef;">
                        <div>
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px;">Invoice
                                Number</small>
                            <span id="modal-invoice-no" class="fw-bold text-dark">-</span>
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px;">Total
                                Settled</small>
                            <span id="modal-total-paid" class="fw-bold text-success fs-5">₱ 0.00</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="history-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 text-muted" style="font-size: 12px;">DATE</th>
                                    <th class="border-0 text-muted" style="font-size: 12px;">REF NO.</th>
                                    <th class="border-0 text-muted" style="font-size: 12px;">METHOD</th>
                                    <th class="border-0 text-muted text-end" style="font-size: 12px;">AMOUNT</th>
                                </tr>
                            </thead>
                            <tbody id="payment-history-data"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4">
                    <button type="button" class="btn btn-light fw-bold text-muted" data-bs-dismiss="modal"
                        style="font-size: 13px;">Close</button>
                    <button type="button" class="btn btn-primary fw-bold" id="printReceiptBtn"
                        style="font-size: 13px;">
                        <i class="fas fa-print me-1"></i> Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('JS src')
    <script src="{{ asset('js/paymentTracker.js') }}"></script>
    <script>
        $(document).ready(function() {
            initPaymentTracker();
        });
    </script>
@endsection
