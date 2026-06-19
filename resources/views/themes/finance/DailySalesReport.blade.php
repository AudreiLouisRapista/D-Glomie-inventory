@extends('themes.main')

{{-- 1. DEFINE PAGE TITLE --}}
@section('title', 'Add daily transaction')


{{-- 2. DEFINE CONTENT --}}
@section('content')
    <link rel="stylesheet" href="{{ asset('css/dailyTransaction.css') }}">


    <!-- Content Header -->
    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold">Daily Sales Report</h1>
        <p> Report your everyday sales </p>
    </div>

    <!-- Main content -->
    <div class="content">

        <form id="dailyReportForm" method="POST">
            @csrf


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
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-calendar"></i></span></div>
                                            <input type="date" name="report_date"
                                                class="form-control @error('report_date') is-invalid @enderror"
                                                id="reportDate" value="{{ old('report_date', date('Y-m-d')) }}" required>
                                            @error('report_date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Branch / Location <span class="text-danger">*</span></label>
                                        <select name="branch"
                                            class="form-control select2bs4 @error('branch') is-invalid @enderror"
                                            id="branchSelect" style="width:100%">
                                            <option value="">— Select Branch —</option>
                                            <option value="bayugan3" {{ old('branch') == 'bayugan3' ? 'selected' : '' }}>
                                                Bayugan 3</option>
                                            <option value="barobo" {{ old('branch') == 'barobo' ? 'selected' : '' }}>
                                                Barobo</option>
                                            <option value="rosario" {{ old('branch') == 'rosario' ? 'selected' : '' }}>
                                                Rosario</option>
                                            <option value="main" {{ old('branch') == 'main' ? 'selected' : '' }}>
                                                Main Branch</option>
                                        </select>
                                        @error('branch')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Prepared By <span class="text-danger">*</span></label>
                                        <select name="prepared_by"
                                            class="form-control select2bs4 @error('prepared_by') is-invalid @enderror"
                                            id="preparedBy" style="width:100%">
                                            <option value="">— Select Staff —</option>
                                            <option value="nanok" {{ old('prepared_by') == 'nanok' ? 'selected' : '' }}>
                                                Nanok</option>
                                            <option value="pete" {{ old('prepared_by') == 'pete' ? 'selected' : '' }}>
                                                Pete</option>
                                            <option value="sypot" {{ old('prepared_by') == 'sypot' ? 'selected' : '' }}>
                                                Sypot</option>
                                            <option value="other" {{ old('prepared_by') == 'other' ? 'selected' : '' }}>
                                                Other</option>
                                        </select>
                                        @error('prepared_by')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                {{-- Purchases / Stock In --}}
                <div class="col-md-6">
                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-boxes mr-2"></i>Purchases / Stock In</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool btn-sm" id="addPurchaseRow" title="Add row">
                                    <i class="fas fa-plus text-warning"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Source / Supplier</th>
                                        <th style="width:130px">Amount (₱)</th>
                                        <th style="width:40px"></th>
                                    </tr>
                                </thead>
                                <tbody id="purchasesTbody">
                                    <tr>
                                        <td>
                                            <select name="purchases[0][supplier]"
                                                class="form-control form-control-sm select2bs4-supplier" style="width:100%">
                                                <option value="">— Supplier —</option>
                                                <option value="lone1_nanok" selected>From Lone 1 (Nanok)</option>
                                                <option value="lone2">From Lone 2</option>
                                                <option value="distributor">Distributor</option>
                                                <option value="direct">Direct Purchase</option>
                                            </select>
                                        </td>

                                        <td><input type="number" name="purchases[0][amount]"
                                                class="form-control form-control-sm purchase-amount" placeholder="0.00"
                                                value="0.00" step="0.01" min="0"></td>
                                        <td><button type="button" class="btn btn-xs btn-danger remove-row"><i
                                                    class="fas fa-times"></i></button></td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="purchases[0][supplier]"
                                                class="form-control form-control-sm select2bs4-supplier" style="width:100%">
                                                <option value="">— Supplier —</option>
                                                <option value="lone1_nanok" selected>From Lone 1 (Nanok)</option>
                                                <option value="lone2">From Lone 2</option>
                                                <option value="distributor">Distributor</option>
                                                <option value="direct">Direct Purchase</option>
                                            </select>
                                        </td>

                                        <td><input type="number" name="purchases[0][amount]"
                                                class="form-control form-control-sm purchase-amount" placeholder="0.00"
                                                value="0.00" step="0.01" min="0"></td>
                                        <td><button type="button" class="btn btn-xs btn-danger remove-row"><i
                                                    class="fas fa-times"></i></button></td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="purchases[0][supplier]"
                                                class="form-control form-control-sm select2bs4-supplier"
                                                style="width:100%">
                                                <option value="">— Supplier —</option>
                                                <option value="lone1_nanok" selected>From Lone 1 (Nanok)</option>
                                                <option value="lone2">From Lone 2</option>
                                                <option value="distributor">Distributor</option>
                                                <option value="direct">Direct Purchase</option>
                                            </select>
                                        </td>

                                        <td><input type="number" name="purchases[0][amount]"
                                                class="form-control form-control-sm purchase-amount" placeholder="0.00"
                                                value="0.00" step="0.01" min="0"></td>
                                        <td><button type="button" class="btn btn-xs btn-danger remove-row"><i
                                                    class="fas fa-times"></i></button></td>

                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="total-row">
                                        <td class="text-right">TOTAL</td>
                                        <td><input type="number" class="form-control form-control-sm font-weight-bold"
                                                id="totalPurchases" readonly placeholder="0.00"></td>
                                        <td></td>
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
                            <h3 class="card-title"><i class="fas fa-truck-loading mr-2"></i>Stock Out</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool btn-sm" id="addStockOutRow" title="Add row">
                                    <i class="fas fa-plus text-secondary"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Destination</th>
                                        <th style="width:130px">Amount (₱)</th>
                                        <th style="width:40px"></th>
                                    </tr>
                                </thead>
                                <tbody id="stockOutTbody">
                                    @foreach (['bayugan3' => 'Bayugan 3', 'barobo' => 'Barobo', 'rosario' => 'Rosario'] as $val => $label)
                                        <tr>
                                            <td>
                                                <select name="stock_out[{{ $loop->index }}][destination]"
                                                    class="form-control form-control-sm select2bs4-dest"
                                                    style="width:100%">
                                                    <option value="bayugan3" {{ $val == 'bayugan3' ? 'selected' : '' }}>
                                                        Bayugan 3</option>
                                                    <option value="barobo" {{ $val == 'barobo' ? 'selected' : '' }}>
                                                        Barobo</option>
                                                    <option value="rosario" {{ $val == 'rosario' ? 'selected' : '' }}>
                                                        Rosario</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </td>
                                            <td><input type="number" name="stock_out[{{ $loop->index }}][amount]"
                                                    class="form-control form-control-sm stockout-amount"
                                                    placeholder="0.00" value="0.00" step="0.01" min="0">
                                            </td>
                                            <td><button type="button" class="btn btn-xs btn-danger remove-row"><i
                                                        class="fas fa-times"></i></button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="total-row">
                                        <td class="text-right">TOTAL STOCK OUT</td>
                                        <td><input type="number" class="form-control form-control-sm font-weight-bold"
                                                id="totalStockOut" readonly placeholder="0.00"></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>{{-- /ROW 2 --}}


            <div class="row">

                {{-- Sales --}}
                <div class="col-md-6">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-cash-register mr-2"></i>Sales</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-section-title">Sales Breakdown</div>

                            @php
                                $salesItems = [
                                    ['id' => 'regularSales', 'name' => 'regular_sales', 'label' => 'Regular Sales'],
                                    ['id' => 'ecoBagSales', 'name' => 'eco_bag_sales', 'label' => 'Eco Bag Sales'],
                                    ['id' => 'eggTraySales', 'name' => 'egg_tray_sales', 'label' => 'Egg Tray'],
                                    ['id' => 'riceSales', 'name' => 'rice_sales', 'label' => 'Rice'],
                                    ['id' => 'eggSales', 'name' => 'egg_sales', 'label' => 'Egg'],
                                    ['id' => 'beerSales', 'name' => 'beer_sales', 'label' => 'Beer'],
                                    ['id' => 'cokeSales', 'name' => 'coke_sales', 'label' => 'Coke / Softdrinks'],
                                ];
                            @endphp

                            @foreach ($salesItems as $item)
                                <div class="form-group row mb-2">
                                    <label class="col-sm-6 col-form-label col-form-label-sm">{{ $item['label'] }}</label>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend"><span class="input-group-text">₱</span>
                                            </div>
                                            <input type="number" id="{{ $item['id'] }}" name="{{ $item['name'] }}"
                                                class="form-control sale-item" placeholder="0.00"
                                                value="{{ old($item['name'], '0.00') }}" step="0.01" min="0">
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <hr>

                            <div class="form-group row mb-2">
                                <label class="col-sm-6 col-form-label col-form-label-sm font-weight-bold">GROSS
                                    SALES</label>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                                        <input type="number" class="form-control font-weight-bold text-success"
                                            id="grossSales" readonly placeholder="0.00">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label class="col-sm-6 col-form-label col-form-label-sm text-danger">Less: Expenses +
                                    Stock in Cash Sales</label>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                                        <input type="number" name="less_expenses_stock" id="lessExpensesStock"
                                            class="form-control" placeholder="0.00"
                                            value="{{ old('less_expenses_stock', '0.00') }}" step="0.01"
                                            min="0">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <label class="col-sm-6 col-form-label col-form-label-sm font-weight-bold text-primary">NET
                                    SALES</label>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                                        <input type="number" class="form-control font-weight-bold text-primary"
                                            id="netSales" readonly placeholder="0.00">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

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
                                            <td><span class="badge badge-secondary">₱{{ number_format($denom) }}</span>
                                            </td>
                                            <td>
                                                <input type="number" name="denom_{{ $denom }}_count"
                                                    class="form-control form-control-sm denom-count"
                                                    data-denom="{{ $denom }}" placeholder="0"
                                                    value="{{ old('denom_' . $denom . '_count', 0) }}" min="0">
                                            </td>
                                            <td>
                                                <input type="number"
                                                    class="form-control form-control-sm computed denom-total" readonly
                                                    value="0">
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="table-light">
                                        <td colspan="2" class="font-weight-bold text-right pr-2">Coins</td>
                                        <td>
                                            <input type="number" name="coins_amount" id="coinsAmount"
                                                class="form-control form-control-sm" placeholder="0.00"
                                                value="{{ old('coins_amount', '0.00') }}" step="0.01" min="0">
                                        </td>
                                    </tr>
                                    <tr class="total-row">
                                        <td colspan="2" class="text-right">TOTAL CASH SALES</td>
                                        <td><input type="number" class="form-control form-control-sm font-weight-bold"
                                                id="totalCashSales" readonly placeholder="0.00"></td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="px-3 pt-3 pb-2">
                                <div class="form-section-title">GCash</div>

                                <div class="form-group row mb-2">
                                    <label class="col-sm-7 col-form-label col-form-label-sm">Initial Deposit</label>
                                    <div class="col-sm-5">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend"><span class="input-group-text">₱</span>
                                            </div>
                                            <input type="number" name="gcash_init_deposit" id="gcashInitDeposit"
                                                class="form-control" placeholder="0.00"
                                                value="{{ old('gcash_init_deposit', '0.00') }}" step="0.01"
                                                min="0">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-2">
                                    <label class="col-sm-7 col-form-label col-form-label-sm font-weight-bold">TOTAL
                                        SALES (Cash + GCash)</label>
                                    <div class="col-sm-5">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend"><span class="input-group-text">₱</span>
                                            </div>
                                            <input type="number" class="form-control font-weight-bold"
                                                id="totalAllSales" readonly placeholder="0.00">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <label class="col-sm-7 col-form-label col-form-label-sm font-weight-bold"
                                        id="diffLabel">Difference</label>
                                    <div class="col-sm-5">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend"><span class="input-group-text">₱</span>
                                            </div>
                                            <input type="number" class="form-control font-weight-bold"
                                                id="cashDifference" readonly placeholder="0.00">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /ROW 3 --}}

            <div class="row">
                {{-- Expenses --}}
                <div class="col-md-6">
                    <div class="card card-danger card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-receipt mr-2"></i>Expenses</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-section-title">Operating Expenses</div>

                            @php
                                $expenses = [
                                    [
                                        'id' => 'cfRegSales',
                                        'name' => 'cf_reg_sales',
                                        'label' => 'CF for Reg. Sales (5%)',
                                    ],
                                    ['id' => 'maintenance', 'name' => 'maintenance', 'label' => 'Maintenance'],
                                    ['id' => 'taxPayment', 'name' => 'tax_payment', 'label' => 'Tax Payment'],
                                    [
                                        'id' => 'mayorsPermit',
                                        'name' => 'mayors_permit',
                                        'label' => "Mayor's Permit",
                                    ],
                                    ['id' => 'expPete', 'name' => 'exp_pete', 'label' => 'Pete'],
                                    ['id' => 'expSypot', 'name' => 'exp_sypot', 'label' => 'Sypot'],
                                ];
                            @endphp

                            @foreach ($expenses as $exp)
                                <div class="form-group row mb-2">
                                    <label class="col-sm-6 col-form-label col-form-label-sm">{{ $exp['label'] }}</label>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend"><span class="input-group-text">₱</span>
                                            </div>
                                            <input type="number" id="{{ $exp['id'] }}" name="{{ $exp['name'] }}"
                                                class="form-control expense-item" placeholder="0.00"
                                                value="{{ old($exp['name'], '0.00') }}" step="0.01" min="0">
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="form-section-title mt-3">Other Expenses</div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-6 col-form-label col-form-label-sm">Other / Miscellaneous</label>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                                        <input type="number" name="other_expenses" id="otherExpenses"
                                            class="form-control expense-item" placeholder="0.00"
                                            value="{{ old('other_expenses', '0.00') }}" step="0.01" min="0">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <label class="col-sm-6 col-form-label col-form-label-sm font-weight-bold">TOTAL
                                    EXPENSES</label>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">₱</span></div>
                                        <input type="number" class="form-control font-weight-bold" id="totalExpenses"
                                            readonly placeholder="0.00">
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
                            <h3 class="card-title"><i class="fas fa-calculator mr-2"></i>Net Income Summary</h3>
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
                                        <td class="text-right font-weight-bold text-danger" id="summStockOut">₱0.00
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Less: Purchases / Stock In −</td>
                                        <td class="text-right font-weight-bold text-danger" id="summPurchases">₱0.00
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Less: Total Expenses −</td>
                                        <td class="text-right font-weight-bold text-danger" id="summExpenses">₱0.00
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <hr class="mt-1 mb-1">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-dark" style="font-size:1rem;">NET INCOME</td>
                                        <td class="text-right">
                                            <span class="net-income-display" id="netIncomeDisplay">₱0.00</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="form-group mt-3 mb-0">
                                <label>Remarks / Notes</label>
                                <textarea name="remarks" class="form-control" rows="3"
                                    placeholder="Enter any notes or remarks for this report...">{{ old('remarks') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- /ROW 4 --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card card-outline">
                        <div class="card-body text-right">
                            <a href="{{ url()->previous() }}" class="btn btn-default mr-2">
                                <i class="fas fa-arrow-left mr-1"></i>Back
                            </a>
                            <button type="reset" class="btn btn-secondary mr-2">
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

    </div>
@endsection

@section('JS src')
    <script src="{{ asset('js/dailySalesReport.js') }}"></script>
@endsection
