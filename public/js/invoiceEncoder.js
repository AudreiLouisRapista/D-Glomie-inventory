function initInvoiceEncoder(allProducts) {
    $(document).ready(function () {

        // 1. Add Row Logic
        $('#addRow').on('click', function () {
            let newRow = `
                <tr class="item-row">
                    <td><input type="number" name="CSquantity[]" class="form-control CSquantity" value="1"></td>
                    <td><input type="text" name="productName[]" list="productData" class="form-control productName" required></td>
                    <td><input type="number" name="Quantinumber[]" class="form-control Quantinumber" value="0" readonly></td>
                    <td><input type="number" name="productSize[]" class="form-control productSize" value="1" readonly></td>
                    <td><input type="text" name="perishableType[]" class="form-control perishableType bg-light" readonly placeholder="-"></td>
                    <td class="expiry-column" style="display: none;">
                        <div class="expiry-wrapper" style="display: none;">
                            <input type="date" name="expdate[]" class="form-control expdate">
                        </div>
                    </td>
                    <td><input type="number" name="unitPrice[]" class="form-control unitPrice" step="0.01"></td>
                    <td class="totalPrice fw-bold">0.00</td>
                    <td class="row-total fw-bold text-primary">0.00</td>
                    <td><button type="button" class="btn btn-outline-danger btn-sm border-0 remove-row"><i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            $('#itemRows').append(newRow);
            toggleExpiryHeader();
        });

        // 2. Dynamic Datalist (Shows only Top 10)
        $(document).on('input', '.productName', function () {
            let inputVal = $(this).val().toLowerCase();
            let $datalist = $('#productData');
            $datalist.empty();

            if (inputVal.length > 0) {
                let matches = allProducts.filter(p =>
                    p.product_name.toLowerCase().includes(inputVal)
                ).slice(0, 10);

                matches.forEach(p => {
                    $datalist.append(`<option value="${p.product_name}">`);
                });
            }
        });

        $(document).on('input', '.productName', function() {
            let inputVal = $(this).val().toLowerCase();
            let $row = $(this).closest('tr');
            let $datalist = $('#productData');
            $datalist.empty();

            // If input is cleared, reset the row details
            if (inputVal.length === 0) {
                $row.find('.Quantinumber').val(0);
                $row.find('.productSize').val(1);
                $row.find('.perishableType').val('');
                $row.find('.expdate').val('');
                $row.find('.expiry-wrapper').hide();
                $row.find('.totalPrice').text('0.00');
                $row.find('.row-total').text('0.00');
                toggleExpiryHeader();
                calculateTotals();
                return;
            }

            if (inputVal.length > 0) {
                let matches = allProducts.filter(p =>
                    p.product_name.toLowerCase().includes(inputVal)
                ).slice(0, 10);

                matches.forEach(p => {
                    $datalist.append(`<option value="${p.product_name}">`);
                });
            }
        });

        // 3. Selection Logic (Triggered on change/select)
        $(document).on('change', '.productName', function () {
            let val = $(this).val();
            let $row = $(this).closest('tr');
            let product = allProducts.find(p => p.product_name === val);

            if (product) {
                let typeValue = product.perishable_type ? product.perishable_type.toLowerCase() :
                    'non-perishable';
                $row.find('.perishableType').val(typeValue);
                $row.find('.Quantinumber').val(product.product_quantity || 0);
                $row.find('.productSize').val(product.product_size || 0);

                handleExpiryVisibility($row, typeValue);
                calculateTotals();
            }
        });

        // 4. Expiry Visibility Logic
        function handleExpiryVisibility($row, typeValue) {
            let $wrapper = $row.find('.expiry-wrapper');
            let $input = $row.find('.expdate');

            // Shows date only if 'perishable' is found but not 'non-perishable'
            if (typeValue.includes('perishable') && !typeValue.includes('non')) {
                $wrapper.show();
                $input.attr('required', 'required');
            } else {
                $wrapper.hide();
                $input.removeAttr('required').val('');
            }
            toggleExpiryHeader();
        }

        // 5. Global Table Header Visibility
        function toggleExpiryHeader() {
            let anyVisible = false;
            $('.perishableType').each(function () {
                let val = $(this).val().toLowerCase();
                if (val.includes('perishable') && !val.includes('non')) {
                    anyVisible = true;
                    return false;
                }
            });
            $('.expiry-column').toggle(anyVisible);
        }

        // 6. Calculations & Removal
        $(document).on('click', '.remove-row', function () {
            $(this).closest('tr').remove();
            calculateTotals();
            toggleExpiryHeader();
        });

        $(document).on('input', '.CSquantity, .unitPrice, .Quantinumber, .productSize', function () {
            calculateTotals();
        });

        function calculateTotals() {
            let grossTotal = 0;
            $('.item-row').each(function () {
                let qty = parseFloat($(this).find('.CSquantity').val()) || 0;
                let up = parseFloat($(this).find('.unitPrice').val()) || 0;
                let qpu = parseFloat($(this).find('.Quantinumber').val()) || 0;
                let tie = parseFloat($(this).find('.productSize').val()) || 0;

                let priceCalculated = qpu * tie * up;
                $(this).find('.totalPrice').text(priceCalculated.toFixed(2));

                let rowTotal = priceCalculated * qty;
                $(this).find('.row-total').text(rowTotal.toFixed(2));
                grossTotal += rowTotal;
            });

            let vatableSales = grossTotal / 1.12;
            let vatAmount = grossTotal - vatableSales;

            $('#gross_total').text('₱' + grossTotal.toLocaleString(undefined, {
                minimumFractionDigits: 2
            }));
            $('#vatable_sales').text('₱' + vatableSales.toLocaleString(undefined, {
                minimumFractionDigits: 2
            }));
            $('#vat_amount').text('₱' + vatAmount.toLocaleString(undefined, {
                minimumFractionDigits: 2
            }));
            $('#grand_total').text('₱' + grossTotal.toLocaleString(undefined, {
                minimumFractionDigits: 2
            }));

            $('#gross_total_raw').val(grossTotal.toFixed(2));
            $('#vat_amount_raw').val(vatAmount.toFixed(2));
            $('#grand_total_raw').val(grossTotal.toFixed(2));
        }

        toggleExpiryHeader();
    });
}