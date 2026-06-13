$(function() {

                // ── Init Select2 ──────────────────────────────────────────────────────────
                function initSelect2(ctx) {
                    $(ctx || document).find('.select2bs4').select2({
                        theme: 'bootstrap4',
                        width: '100%'
                    });
                    $(ctx || document).find('.select2bs4-supplier').select2({
                        theme: 'bootstrap4',
                        width: '100%',
                        placeholder: '— Supplier —'
                    });
                    $(ctx || document).find('.select2bs4-dest').select2({
                        theme: 'bootstrap4',
                        width: '100%'
                    });
                }
                initSelect2();

                // ── Helpers ───────────────────────────────────────────────────────────────
                function numVal(selector) {
                    return parseFloat($(selector).val()) || 0;
                }

                function fmt(n) {
                    return '₱' + n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                }

                // ── Recalculate all derived fields ────────────────────────────────────────
                function recalc() {

                    // Purchases total
                    let purchTotal = 0;
                    $('.purchase-amount').each(function() {
                        purchTotal += parseFloat($(this).val()) || 0;
                    });
                    $('#totalPurchases').val(purchTotal.toFixed(2));

                    // Expenses total
                    let expTotal = 0;
                    $('.expense-item').each(function() {
                        expTotal += parseFloat($(this).val()) || 0;
                    });
                    $('#totalExpenses').val(expTotal.toFixed(2));

                    // Gross Sales
                    let grossSales = 0;
                    $('.sale-item').each(function() {
                        grossSales += parseFloat($(this).val()) || 0;
                    });
                    $('#grossSales').val(grossSales.toFixed(2));

                    // Net Sales
                    $('#netSales').val((grossSales - numVal('#lessExpensesStock')).toFixed(2));

                    // Denominations
                    let denomTotal = 0;
                    $('.denom-count').each(function() {
                        const denom = parseInt($(this).data('denom'));
                        const count = parseInt($(this).val()) || 0;
                        const total = denom * count;
                        $(this).closest('tr').find('.denom-total').val(total);
                        denomTotal += total;
                    });
                    const totalCash = denomTotal + numVal('#coinsAmount');
                    $('#totalCashSales').val(totalCash.toFixed(2));

                    // GCash + total
                    const totalAllSales = totalCash + numVal('#gcashInitDeposit');
                    $('#totalAllSales').val(totalAllSales.toFixed(2));

                    // Over / Short
                    const diff = totalAllSales - grossSales;
                    $('#cashDifference').val(diff.toFixed(2));
                    if (diff > 0) {
                        $('#cashDifference').removeClass('text-danger').addClass('text-success');
                        $('#diffLabel').text('Difference (OVER)');
                    } else if (diff < 0) {
                        $('#cashDifference').removeClass('text-success').addClass('text-danger');
                        $('#diffLabel').text('Difference (SHORT)');
                    } else {
                        $('#cashDifference').removeClass('text-success text-danger');
                        $('#diffLabel').text('Difference');
                    }

                    // Stock Out total
                    let stockOutTotal = 0;
                    $('.stockout-amount').each(function() {
                        stockOutTotal += parseFloat($(this).val()) || 0;
                    });
                    $('#totalStockOut').val(stockOutTotal.toFixed(2));

                    // Net Income
                    const netIncome = grossSales - stockOutTotal - purchTotal - expTotal;
                    $('#summGrossSales').text(fmt(grossSales));
                    $('#summStockOut').text(fmt(stockOutTotal));
                    $('#summPurchases').text(fmt(purchTotal));
                    $('#summExpenses').text(fmt(expTotal));
                    $('#netIncomeDisplay').text(fmt(netIncome)).toggleClass('negative', netIncome < 0);
                }

                $(document).on('input change', 'input[type="number"], select', recalc);
                recalc();

                // ── Dynamic row index helper ───────────────────────────────────────────────
                function reindexRows(tbodyId, namePrefix) {
                    $('#' + tbodyId + ' tr').each(function(i) {
                        $(this).find('select, input').each(function() {
                            const name = $(this).attr('name') || '';
                            $(this).attr('name', name.replace(/\[\d+\]/, '[' + i + ']'));
                        });
                    });
                }

                // ── Add Purchase row ──────────────────────────────────────────────────────
                $('#addPurchaseRow').on('click', function() {
                    const idx = $('#purchasesTbody tr').length;
                    const row = $(`
                <tr>
                    <td>
                    <select name="purchases[${idx}][supplier]" class="form-control form-control-sm select2bs4-supplier" style="width:100%">
                        <option value="">— Supplier —</option>
                        <option value="lone1_nanok">From Lone 1 (Nanok)</option>
                        <option value="lone2">From Lone 2</option>
                        <option value="distributor">Distributor</option>
                        <option value="direct">Direct Purchase</option>
                    </select>
                    </td>
                    <td><input type="number" name="purchases[${idx}][amount]" class="form-control form-control-sm purchase-amount" placeholder="0.00" value="0" step="0.01" min="0"></td>
                    <td><button type="button" class="btn btn-xs btn-danger remove-row"><i class="fas fa-times"></i></button></td>
                </tr>`);
                                $('#purchasesTbody').append(row);
                                initSelect2(row);
                                recalc();
                            });

                            // ── Add Stock Out row ─────────────────────────────────────────────────────
                            $('#addStockOutRow').on('click', function() {
                                const idx = $('#stockOutTbody tr').length;
                                const row = $(`
                <tr>
                    <td>
                    <select name="stock_out[${idx}][destination]" class="form-control form-control-sm select2bs4-dest" style="width:100%">
                        <option value="bayugan3">Bayugan 3</option>
                        <option value="barobo">Barobo</option>
                        <option value="rosario">Rosario</option>
                        <option value="other">Other</option>
                    </select>
                    </td>
                    <td><input type="number" name="stock_out[${idx}][amount]" class="form-control form-control-sm stockout-amount" placeholder="0.00" value="0" step="0.01" min="0"></td>
                    <td><button type="button" class="btn btn-xs btn-danger remove-row"><i class="fas fa-times"></i></button></td>
                </tr>`);
                    $('#stockOutTbody').append(row);
                    initSelect2(row);
                    recalc();
                });

                // ── Remove row ────────────────────────────────────────────────────────────
                $(document).on('click', '.remove-row', function() {
                    $(this).closest('tr').remove();
                    recalc();
                });

});