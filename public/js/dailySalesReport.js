function initDailyReport(routes) {
    $(document).ready(function () {

        // ==========================================
        // LOAD DATA WHEN DATE CHANGES
        // ==========================================
        $('#reportDate').on('change', function () {
            var date = $(this).val();
            if (!date) return;
            loadPurchases(date);
            loadDailySales(date);
            loadStockOut(date);
        });

        // Auto-load on page load
        var initialDate = $('#reportDate').val();
        if (initialDate) {
            loadPurchases(initialDate);
            loadDailySales(initialDate);
            loadStockOut(initialDate);
        }


        // ==========================================
        // LOAD PURCHASES
        // ==========================================
        function loadPurchases(date) {
            $('#purchasesLoading').show();
            $('#purchasesTbody').html('');

            $.ajax({
                url: routes.getPurchasesByDateUrl,
                method: 'GET',
                data: { date: date },
                success: function (data) {
                    $('#purchasesLoading').hide();
                    var total = 0;

                    if (data.length === 0) {
                        $('#purchasesTbody').html(`
                            <tr id="noPurchasesRow">
                                <td colspan="3" class="text-center text-muted py-3">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    No purchases found for selected date
                                </td>
                            </tr>
                        `);
                        $('#purchaseCount').text('0 records');
                        $('#totalPurchases').val('0.00');
                        recalculate();
                        return;
                    }

                    $.each(data, function (i, row) {
                        var amount = parseFloat(row.amount) || 0;
                        total += amount;
                        $('#purchasesTbody').append(`
                            <tr>
                                <td>${row.invoice_number}</td>
                                <td>${row.supplier_name}</td>
                                <td>
                                    <input type="number" class="form-control form-control-sm bg-light"
                                        value="${amount.toFixed(2)}" readonly>
                                    <input type="hidden" name="purchase_ids[]" value="${row.purchase_id}">
                                </td>
                            </tr>
                        `);
                    });

                    $('#purchaseCount').text(data.length + ' records');
                    $('#totalPurchases').val(total.toFixed(2));
                    recalculate();
                },
                error: function () {
                    $('#purchasesLoading').hide();
                    $('#purchasesTbody').html('<tr><td colspan="3" class="text-center text-danger">Failed to load purchases.</td></tr>');
                }
            });
        }


        // ==========================================
        // LOAD DAILY SALES
        // ==========================================
        function loadDailySales(date) {
            $('#salesLoading').show();
            $('#salesTbody').html('');

            $.ajax({
                url: routes.getDailySalesByDateUrl,
                method: 'GET',
                data: { date: date },
                success: function (data) {
                    $('#salesLoading').hide();
                    var grossTotal = 0;

                    if (data.length === 0) {
                        $('#salesTbody').html(`
                            <tr id="noSalesRow">
                                <td colspan="3" class="text-center text-muted py-3">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    No sales found for selected date
                                </td>
                            </tr>
                        `);
                        $('#salesCount').text('0 records');
                        $('#grossSales').val('0.00');
                        recalculate();
                        return;
                    }

                    $.each(data, function (i, row) {
                        var amount = parseFloat(row.total_amount) || 0;
                        grossTotal += amount;
                        $('#salesTbody').append(`
                            <tr>
                                <td>${row.product_name}</td>
                                <td>${row.quantity_sold}</td>
                                <td>
                                    <input type="number" class="form-control form-control-sm bg-light"
                                        value="${amount.toFixed(2)}" readonly>
                                    <input type="hidden" name="sale_ids[]" value="${row.sale_id}">
                                </td>
                            </tr>
                        `);
                    });

                    $('#salesCount').text(data.length + ' records');
                    $('#grossSales').val(grossTotal.toFixed(2));
                    recalculate();
                },
                error: function () {
                    $('#salesLoading').hide();
                    $('#salesTbody').html('<tr><td colspan="3" class="text-center text-danger">Failed to load sales.</td></tr>');
                }
            });
        }


        // ==========================================
        // LOAD STOCK OUT
        // ==========================================
        function loadStockOut(date) {
            $('#stockOutLoading').show();
            $('#stockOutTbody').html('');

            $.ajax({
                url: routes.getStockOutByDateUrl,
                method: 'GET',
                data: { date: date },
                success: function (data) {
                    $('#stockOutLoading').hide();
                    var total = 0;

                    if (data.length === 0) {
                        $('#stockOutTbody').html(`
                            <tr id="noStockOutRow">
                                <td colspan="3" class="text-center text-muted py-3">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    No stock out found for selected date
                                </td>
                            </tr>
                        `);
                        $('#stockOutCount').text('0 records');
                        $('#totalStockOut').val('0.00');
                        recalculate();
                        return;
                    }

                    $.each(data, function (i, row) {
                        var amount = parseFloat(row.amount) || 0;
                        total += amount;
                        $('#stockOutTbody').append(`
                            <tr>
                                <td>${row.product_name}</td>
                                <td>${row.destination}</td>
                                <td>
                                    <input type="number" class="form-control form-control-sm bg-light"
                                        value="${amount.toFixed(2)}" readonly>
                                    <input type="hidden" name="transfer_ids[]" value="${row.transfer_id}">
                                </td>
                            </tr>
                        `);
                    });

                    $('#stockOutCount').text(data.length + ' records');
                    $('#totalStockOut').val(total.toFixed(2));
                    recalculate();
                },
                error: function () {
                    $('#stockOutLoading').hide();
                    $('#stockOutTbody').html('<tr><td colspan="3" class="text-center text-danger">Failed to load stock out.</td></tr>');
                }
            });
        }


        // ==========================================
        // DENOMINATION — auto calculate per bill
        // ==========================================
        $(document).on('input', '.denom-count', function () {
            var denom  = parseFloat($(this).data('denom')) || 0;
            var count  = parseFloat($(this).val()) || 0;
            var amount = denom * count;
            $('#denom_total_' + denom).val(amount.toFixed(2));
            recalculate();
        });

        $(document).on('input', '#coinsAmount, #gcashInitDeposit', function () {
            recalculate();
        });


        // ==========================================
        // EXPENSES — add/remove row
        // ==========================================
        $('#addExpenseRow').on('click', function () {
            $('#expensesTbody').append(`
                <tr class="expense-row">
                    <td>
                        <input type="text" name="expense_label[]"
                            class="form-control form-control-sm"
                            placeholder="e.g. Maintenance">
                    </td>
                    <td>
                        <input type="number" name="expense_amount[]"
                            class="form-control form-control-sm expense-amount"
                            placeholder="0.00" value="0" step="0.01" min="0">
                    </td>
                    <td>
                        <button type="button" class="btn btn-xs btn-danger remove-expense-row">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            `);
        });

        $(document).on('click', '.remove-expense-row', function () {
            if ($('.expense-row').length > 1) {
                $(this).closest('.expense-row').remove();
                recalculate();
            } else {
                Swal.fire('Warning', 'At least one expense row is required.', 'warning');
            }
        });

        $(document).on('input', '.expense-amount', function () {
            recalculate();
        });


        // ==========================================
        // RECALCULATE ALL
        // ==========================================
        function recalculate() {
            var grossSales     = parseFloat($('#grossSales').val()) || 0;
            var totalPurchases = parseFloat($('#totalPurchases').val()) || 0;
            var totalStockOut  = parseFloat($('#totalStockOut').val()) || 0;

            // Total Expenses
            var totalExpenses = 0;
            $('.expense-amount').each(function () {
                totalExpenses += parseFloat($(this).val()) || 0;
            });
            $('#totalExpenses').val(totalExpenses.toFixed(2));

            // Less Expenses + Stock
            var lessExpensesStock = totalExpenses + totalPurchases;
            $('#lessExpensesStock').val(lessExpensesStock.toFixed(2));

            // Net Sales
            var netSales = grossSales - lessExpensesStock;
            $('#netSales').val(netSales.toFixed(2));

            // Total Cash Sales (denom + coins)
            var totalCash = 0;
            $('.denom-count').each(function () {
                var denom = parseFloat($(this).data('denom')) || 0;
                var count = parseFloat($(this).val()) || 0;
                totalCash += denom * count;
            });
            var coins = parseFloat($('#coinsAmount').val()) || 0;
            totalCash += coins;
            $('#totalCashSales').val(totalCash.toFixed(2));

            // Total Sales (Cash + GCash)
            var gcash       = parseFloat($('#gcashInitDeposit').val()) || 0;
            var totalSales  = totalCash + gcash;
            $('#totalAllSales').val(totalSales.toFixed(2));

            // Difference
            var difference = totalSales - grossSales;
            $('#cashDifference').val(difference.toFixed(2));

            // Net Income Summary
            $('#summGrossSales').text('₱' + grossSales.toFixed(2));
            $('#summStockOut').text('₱' + totalStockOut.toFixed(2));
            $('#summPurchases').text('₱' + totalPurchases.toFixed(2));
            $('#summExpenses').text('₱' + totalExpenses.toFixed(2));

            var netIncome = grossSales - totalStockOut - totalPurchases - totalExpenses;
            $('#netIncomeDisplay').text('₱' + netIncome.toFixed(2));
            $('#netIncomeInput').val(netIncome.toFixed(2));

            // Color net income
            if (netIncome > 0) {
                $('#netIncomeDisplay').css('color', '#28a745');
            } else if (netIncome < 0) {
                $('#netIncomeDisplay').css('color', '#dc3545');
            } else {
                $('#netIncomeDisplay').css('color', '#6c757d');
            }
        }


        // ==========================================
        // FORM SUBMIT
        // ==========================================
        $('#dailyReportForm').on('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Submit Daily Report?',
                text: 'Are you sure you want to submit this report?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1a56db',
                confirmButtonText: 'Yes, Submit',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: routes.saveDailyReportUrl,
                        method: 'POST',
                        data: $(this).serialize(),
                        beforeSend: function () {
                            Swal.fire({
                                title: 'Submitting...',
                                allowOutsideClick: false,
                                didOpen: () => { Swal.showLoading(); }
                            });
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Report Submitted!',
                                text: response.save,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function (xhr) {
                            var msg = xhr.responseJSON?.error
                                || xhr.responseJSON?.errorMessage
                                || 'Something went wrong.';
                            Swal.fire({ icon: 'error', title: 'Error', text: msg });
                        }
                    });
                }
            });
        });

        // ==========================================
        // RESET BUTTON
        // ==========================================
        $('#resetBtn').on('click', function () {
            $('#purchasesTbody').html('<tr><td colspan="3" class="text-center text-muted py-3">No purchases found for selected date</td></tr>');
            $('#salesTbody').html('<tr><td colspan="3" class="text-center text-muted py-3">No sales found for selected date</td></tr>');
            $('#stockOutTbody').html('<tr><td colspan="3" class="text-center text-muted py-3">No stock out found for selected date</td></tr>');
            $('#totalPurchases, #totalStockOut, #grossSales').val('0.00');
            recalculate();
        });

    });
}