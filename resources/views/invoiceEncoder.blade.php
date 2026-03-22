@extends('themes.main')

{{-- 1. DEFINE PAGE TITLE --}}
@section('title', 'Invoice Encoder')

{{-- 2. DEFINE CONTENT --}}
@section('content')

    <link rel="stylesheet" href="{{ asset('css/invoiceEncoder.css') }}">
    <script src="{{ asset('js/invoiceEncoder.js') }}"></script>

    <div class="col-sm-6">
        <h1>Invoice Encoder</h1>
    </div>

    <div class="col-12">

        {{-- Blue Hero Header --}}
        <div class="inv-hero">
            <h2><i class="fas fa-file-invoice mr-2"></i> Invoice Encoder</h2>
            <p>Create and manage your invoices</p>
        </div>

        {{-- White Form Panel --}}
        <div class="form-panel">

            {{-- Row 1: Invoice Number + Supplier --}}
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="inv-label">Invoice Number <span class="req">*</span></label>
                    <input type="text" id="invoice_number" class="form-control inv-input" placeholder="INV-001">
                </div>
                <div class="col-md-6 form-group">
                    <label class="inv-label">Supplier Name <span class="req">*</span></label>
                    <select id="supplier_select" class="form-control inv-input" onchange="fillSupplier(this)">
                        <option value="">Choose a supplier...</option>
                        <option value="acme">Acme Corp</option>
                        <option value="global">Global Traders Inc.</option>
                        <option value="prime">Prime Supplies Co.</option>
                    </select>
                </div>
            </div>

            {{-- Row 2: Date + Due Date --}}
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="inv-label">Date <span class="req">*</span></label>
                    <input type="date" id="invoice_date" class="form-control inv-input" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-6 form-group">
                    <label class="inv-label">Due Date <span class="req">*</span></label>
                    <input type="date" id="due_date" class="form-control inv-input">
                </div>
            </div>

            {{-- Supplier Detail Row (shown after selection) --}}
            <div class="row" id="sup-extra" style="display:none;">
                <div class="col-md-5 form-group">
                    <label class="inv-label">Supplier Address</label>
                    <input type="text" id="sup-addr" class="form-control inv-input" readonly>
                </div>
                <div class="col-md-3 form-group">
                    <label class="inv-label">TIN</label>
                    <input type="text" id="sup-tin" class="form-control inv-input" readonly>
                </div>
                <div class="col-md-4 form-group">
                    <label class="inv-label">Payment Terms</label>
                    <select class="form-control inv-input">
                        <option>Net 30</option>
                        <option>Net 15</option>
                        <option>Due on receipt</option>
                        <option>Net 60</option>
                    </select>
                </div>
            </div>

            <hr class="inv-divider">

            {{-- Invoice Items Header --}}
            <div class="items-hd">
                <h5 class="items-title">Invoice Items</h5>
                <button type="button" class="btn btn-add-item" onclick="addRow()">
                    <i class="fas fa-plus"></i> Add Item
                </button>
            </div>

            {{-- Items Table --}}
            <div class="table-responsive">
                <table class="table inv-table" id="inv-table">
                    <thead>
                        <tr>
                            <th style="min-width:280px;">Description</th>
                            <th style="width:110px;text-align:center;">Quantity</th>
                            <th style="width:130px;text-align:center;">Unit Price</th>
                            <th style="width:120px;text-align:right;">Total</th>
                            <th style="width:44px;"></th>
                        </tr>
                    </thead>
                    <tbody id="items-body">
                        {{-- Rows injected by JS --}}
                    </tbody>
                </table>
            </div>

            {{-- Summary --}}
            <div class="summary-section">
                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <div class="sum-row">
                            <span class="sum-label">Net Amount:</span>
                            <span class="sum-value" id="s-net">₱0.00</span>
                        </div>
                        <div class="sum-row">
                            <span class="sum-label">VAT (12%):</span>
                            <span class="sum-value" id="s-vat">₱0.00</span>
                        </div>
                        <hr class="sum-divider">
                        <div class="sum-grand">
                            <span class="sum-grand-label">Gross Amount:</span>
                            <span class="sum-grand-value" id="s-gross">₱0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Save Button --}}
            <div class="save-footer">
                <button type="button" class="btn btn-save-invoice" onclick="saveInvoice()">
                    <i class="fas fa-save"></i> Save Invoice
                </button>
            </div>

        </div>{{-- end .form-panel --}}

    </div>{{-- end .col-12 --}}



@endsection


@section('JS src')

@endsection
