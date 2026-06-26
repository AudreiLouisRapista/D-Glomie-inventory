@extends('themes.main')

{{-- 1. DEFINE PAGE TITLE --}}
@section('title', 'Supplier Payment Tracking')

{{-- 3. DEFINE MAIN CONTENT --}}
@section('content')
    <link rel="stylesheet" href="{{ asset('css/stockInHistory.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero-header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">




    <div class="inv-hero">
        <div class="inv-hero-text">
            <h2 class="font-weight-bold">Stock in History</h2>
            <p>Recent Stock in</p>
        </div>
        <div class="inv-hero-icon">
            <i class="bi bi-receipt"></i>
        </div>
    </div>

    <div class="main-card">
        <div class="table-card-header">
            <div>
                {{-- <h3 class="table-card-title">Stock in list</h3> --}}
            </div>
        </div>


        <div class="table-responsive">

            <!-- Add this before the table to debug -->
            <table id="example2" class="table table-modern">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Supplier</th>
                        <th>Dates</th>
                        <th>Financials</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $row)
                        @php
                            $remaining = $row->invoice_netAmount - ($row->invoice_totalPaid ?? 0);
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

                            <td>
                                <div class="d-flex justify-content-center align-items-center">
                                    {{-- View Button --}}
                                    <button type="button" class="action-btn btn-view mx-1" data-toggle="modal"
                                        data-target="#viewItemsModal{{ $row->id }}">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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

@endsection

@section('JS src')
    <script src="{{ asset('js/stockInHistory.js') }}"></script>
    <script>
        $(document).ready(function() {
            initPaymentTracker();
        });
    </script>
@endsection
