@extends('themes.main')

@section('title', 'Daily Sales Report')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dailyTransaction.css') }}">

    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold">Daily Sales Report</h1>
        <p>Report your everyday sales</p>
    </div>

    <form id="dailyReportForm" method="POST">
        @csrf

        {{-- ==================== --}}
        {{-- REPORT INFORMATION   --}}
        {{-- ==================== --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-calendar-day mr-2"></i>Report Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Report Date <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        </div>
                                        <input type="date" name="report_date" id="reportDate" class="form-control"
                                            value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Branch / Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control bg-light" value="{{ session('branch_name') }}"
                                        readonly>
                                    <input type="hidden" name="branch_id" value="{{ session('branch_id') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Prepared By <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control bg-light" value="{{ session('user_role') }}"
                                        readonly>
                                    <input type="hidden" name="user_id" value="{{ session('id') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== --}}
        {{-- PURCHASES & STOCK OUT --}}
        {{-- ==================== --}}
        <div class="row">

            {{-- Purchases / Stock In --}}
            <div class="col-md-6">
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-boxes mr-2"></i>Purchases / Stock In
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-warning" id="purchaseCount">0 records</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="purchasesLoading" class="text-center py-3 text-muted" style="display:none;">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Loading...
                        </div>
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Supplier</th>
                                    <th style="width:130px">Amount (₱)</th>
                                </tr>
                            </thead>
                            <tbody id="purchasesTbody">
                                <tr id="noPurchasesRow">
                                    <td colspan="3" class="text-center text-muted py-3">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        No purchases found for selected date
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="2" class="text-right font-weight-bold">TOTAL</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm font-weight-bold"
                                            id="totalPurchases" name="total_purchases" readonly placeholder="0.00"
                                            value="0">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Stock Out --}}
            <div class="col-md-6">
                <div class="card card-secondary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-truck-loading mr-2"></i>Stock Out
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-secondary" id="stockOutCount">0 records</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="stockOutLoading" class="text-center py-3 text-muted" style="display:none;">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Loading...
                        </div>
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Destination</th>
                                    <th style="width:130px">Amount (₱)</th>
                                </tr>
                            </thead>
                            <tbody id="stockOutTbody">
                                <tr id="noStockOutRow">
                                    <td colspan="3" class="text-center text-muted py-3">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        No stock out found for selected date
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="2" class="text-right font-weight-bold">TOTAL STOCK OUT</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm font-weight-bold"
                                            id="totalStockOut" name="total_stockout" readonly placeholder="0.00"
                                            value="0">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        {{-- ==================== --}}
        {{-- SALES & Expenses   --}}
        {{-- ==================== --}}
        <div class="row">

            {{-- Sales --}}
            <div class="col-md-6">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cash-register mr-2"></i>Sales
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-success" id="salesCount">0 records</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="salesLoading" class="text-center py-3 text-muted" style="display:none;">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Loading...
                        </div>
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Product</th>
                                    <th style="width:80px">Qty</th>
                                    <th style="width:130px">Amount (₱)</th>
                                </tr>
                            </thead>
                            <tbody id="salesTbody">
                                <tr id="noSalesRow">
                                    <td colspan="3" class="text-center text-muted py-3">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        No sales found for selected date
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="2" class="text-right font-weight-bold text-success">GROSS SALES
                                    </td>
                                    <td>
                                        <input type="number"
                                            class="form-control form-control-sm font-weight-bold text-success"
                                            id="grossSales" name="gross_sales" readonly placeholder="0.00"
                                            value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right text-danger" style="font-size:0.85rem;">
                                        Less: Expenses + Stock in Cash Sales
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" id="lessExpensesStock"
                                            name="less_expenses_stock" readonly placeholder="0.00" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right font-weight-bold text-primary">NET SALES</td>
                                    <td>
                                        <input type="number"
                                            class="form-control form-control-sm font-weight-bold text-primary"
                                            id="netSales" name="net_sales" readonly placeholder="0.00" value="0">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Expenses --}}
            <div class="col-md-6">
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-receipt mr-2"></i>Expenses</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool btn-sm" id="addExpenseRow">
                                <i class="fas fa-plus text-danger"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Description</th>
                                    <th style="width:130px">Amount (₱)</th>
                                    <th style="width:40px"></th>
                                </tr>
                            </thead>
                            <tbody id="expensesTbody">
                                <tr class="expense-row">
                                    <td>
                                        <input type="text" name="expense_label[]" class="form-control form-control-sm"
                                            placeholder="e.g. CF for Reg. Sales (5%)">
                                    </td>
                                    <td>
                                        <input type="number" name="expense_amount[]"
                                            class="form-control form-control-sm expense-amount" placeholder="0.00"
                                            value="0" step="0.01" min="0">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-xs btn-danger remove-expense-row">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td class="text-right font-weight-bold">TOTAL EXPENSES</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm font-weight-bold"
                                            id="totalExpenses" name="total_expenses" readonly placeholder="0.00"
                                            value="0">
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>


        </div>

        {{-- ==================== --}}
        {{-- Cash Count & NET INCOME --}}
        {{-- ==================== --}}
        <div class="row">

            {{-- Cash Count --}}
            <div class="col-md-6">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-coins mr-2"></i>Cash Count</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm denomination-table mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Denom.</th>
                                    <th style="width:80px">× Count</th>
                                    <th style="width:90px">= Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ([1000, 500, 200, 100, 50, 20] as $denom)
                                    <tr>
                                        <td>
                                            <span class="badge badge-secondary">
                                                ₱{{ number_format($denom) }}
                                            </span>
                                        </td>
                                        <td>
                                            <input type="number" name="denom_{{ $denom }}_count"
                                                class="form-control form-control-sm denom-count"
                                                data-denom="{{ $denom }}" placeholder="0" value="0"
                                                min="0">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm denom-total"
                                                id="denom_total_{{ $denom }}" readonly value="0">
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="table-light">
                                    <td colspan="2" class="font-weight-bold text-right pr-2">Coins</td>
                                    <td>
                                        <input type="number" name="coins_amount" id="coinsAmount"
                                            class="form-control form-control-sm" placeholder="0.00" value="0"
                                            step="0.01" min="0">
                                    </td>
                                </tr>
                                <tr class="total-row">
                                    <td colspan="2" class="text-right font-weight-bold">TOTAL CASH SALES</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm font-weight-bold"
                                            id="totalCashSales" name="total_cash_sales" readonly placeholder="0.00"
                                            value="0">
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="px-3 pt-3 pb-2">
                            <div class="form-section-title">GCash</div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-7 col-form-label col-form-label-sm">Initial Deposit</label>
                                <div class="col-sm-5">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₱</span>
                                        </div>
                                        <input type="number" name="gcash_init_deposit" id="gcashInitDeposit"
                                            class="form-control" placeholder="0.00" value="0" step="0.01"
                                            min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-7 col-form-label col-form-label-sm font-weight-bold">
                                    TOTAL SALES (Cash + GCash)
                                </label>
                                <div class="col-sm-5">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₱</span>
                                        </div>
                                        <input type="number" class="form-control font-weight-bold" id="totalAllSales"
                                            name="total_sales" readonly placeholder="0.00" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <label class="col-sm-7 col-form-label col-form-label-sm font-weight-bold">
                                    Difference
                                </label>
                                <div class="col-sm-5">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₱</span>
                                        </div>
                                        <input type="number" class="form-control font-weight-bold" id="cashDifference"
                                            name="difference" readonly placeholder="0.00" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Net Income Summary --}}
            <div class="col-md-6">
                <div class="card card-primary card-outline elevation-2">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calculator mr-2"></i>Net Income Summary
                        </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Gross Sales +</td>
                                    <td class="text-right font-weight-bold" id="summGrossSales">₱0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Less: Stock Out −</td>
                                    <td class="text-right font-weight-bold text-danger" id="summStockOut">₱0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Less: Purchases / Stock In −</td>
                                    <td class="text-right font-weight-bold text-danger" id="summPurchases">₱0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Less: Total Expenses −</td>
                                    <td class="text-right font-weight-bold text-danger" id="summExpenses">₱0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <hr class="mt-1 mb-1">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-dark" style="font-size:1rem;">NET INCOME</td>
                                    <td class="text-right">
                                        <span class="font-weight-bold" id="netIncomeDisplay"
                                            style="font-size:1.3rem;">₱0.00</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- Hidden fields for net income --}}
                        <input type="hidden" name="net_income" id="netIncomeInput">

                        <div class="form-group mt-3 mb-0">
                            <label>Remarks / Notes</label>
                            <textarea name="remarks" class="form-control" rows="3"
                                placeholder="Enter any notes or remarks for this report..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ==================== --}}
        {{-- FOOTER BUTTONS       --}}
        {{-- ==================== --}}
        <div class="row d-flex align-items-stretch">
            <div class="col-12">
                <div class="card card-outline">
                    <div class="card-body text-right">
                        <a href="{{ url()->previous() }}" class="btn btn-default mr-2">
                            <i class="fas fa-arrow-left mr-1"></i>Back
                        </a>
                        <button type="reset" class="btn btn-secondary mr-2" id="resetBtn">
                            <i class="fas fa-undo mr-1"></i>Reset
                        </button>
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-paper-plane mr-2"></i>Submit Report
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>


@endsection

@section('JS src')
    <script src="{{ asset('js/dailySalesReport.js') }}"></script>
    <script>
        $(document).ready(function() {
            initDailyReport({
                getPurchasesByDateUrl: "{{ route('get_purchases_by_date') }}",
                getDailySalesByDateUrl: "{{ route('get_daily_sales_by_date') }}",
                getStockOutByDateUrl: "{{ route('get_stock_out_by_date') }}",
                saveDailyReportUrl: "{{ route('save_daily_report') }}",
            });
        });
    </script>
@endsection
