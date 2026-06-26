function initInvoiceEncoder(allProducts) {
    $(document).ready(function () {

        // ==========================================
        // HELPER — Initialize Select2 on product dropdown (local data)
        // ==========================================
        function initSelect2Product($select) {
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            // Build options from allProducts
            $select.empty().append('<option value="">-- Select Product --</option>');
            allProducts.forEach(function (p) {
                $select.append(
                    $('<option>', {
                        value: p.id,
                        text: p.product_name
                    })
                );
            });

            $select.select2({
                placeholder: 'Search product...',
                allowClear: true,
                width: '100%'
            });
        }

        // Init Select2 on supplier dropdown
        $('.select2-supplier').select2({
            placeholder: 'Choose a supplier...',
            allowClear: true,
            width: '100%'
        });

        // Init Select2 on first product row
        initSelect2Product($('.select2-product').first());


        // ==========================================
        // 1. ADD ROW
        // ==========================================
        $('#addRow').on('click', function () {
            let newRow = `
                <tr class="item-row">
                    <td><input type="number" name="CSquantity[]" class="form-control CSquantity" value="1"></td>
                    <td>
                        <select name="productId[]" class="form-control productName select2-product" style="width: 100%;">
                        </select>
                    </td>
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
            var $newRow = $(newRow);
            $('#itemRows').append($newRow);
            initSelect2Product($newRow.find('.select2-product'));
            toggleExpiryHeader();
        });


        // ==========================================
        // 2. SELECT2 SELECTION LOGIC — auto-fill product details
        // ==========================================
        $(document).on('select2:select', '.select2-product', function (e) {
            var productId = e.params.data.id;
            var $row      = $(this).closest('tr');
            var product   = allProducts.find(p => p.id == productId);

            if (product) {
                let typeValue = product.perishable_type ? product.perishable_type.toLowerCase() : 'non-perishable';
                $row.find('.perishableType').val(typeValue);
                $row.find('.Quantinumber').val(product.bundle_quantity || 0);
                $row.find('.productSize').val(product.bundle_size || 0);

                handleExpiryVisibility($row, typeValue);
                calculateTotals();
            }
        });

        $(document).on('select2:clear', '.select2-product', function () {
            var $row = $(this).closest('tr');
            $row.find('.Quantinumber').val(0);
            $row.find('.productSize').val(1);
            $row.find('.perishableType').val('');
            $row.find('.expdate').val('');
            $row.find('.expiry-wrapper').hide();
            $row.find('.totalPrice').text('0.00');
            $row.find('.row-total').text('0.00');
            toggleExpiryHeader();
            calculateTotals();
        });


        // ==========================================
        // 3. EXPIRY VISIBILITY LOGIC
        // ==========================================
        function handleExpiryVisibility($row, typeValue) {
            let $wrapper = $row.find('.expiry-wrapper');
            let $input   = $row.find('.expdate');

            if (typeValue.includes('perishable') && !typeValue.includes('non')) {
                $wrapper.show();
                $input.attr('required', 'required');
            } else {
                $wrapper.hide();
                $input.removeAttr('required').val('');
            }
            toggleExpiryHeader();
        }


        // ==========================================
        // 4. GLOBAL TABLE HEADER VISIBILITY
        // ==========================================
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


        // ==========================================
        // 5. REMOVE ROW
        // ==========================================
        $(document).on('click', '.remove-row', function () {
            var $row = $(this).closest('tr');
            if ($row.find('.select2-product').hasClass('select2-hidden-accessible')) {
                $row.find('.select2-product').select2('destroy');
            }
            $row.remove();
            calculateTotals();
            toggleExpiryHeader();
        });


        // ==========================================
        // 6. CALCULATIONS
        // ==========================================
        $(document).on('input', '.CSquantity, .unitPrice, .Quantinumber, .productSize', function () {
            calculateTotals();
        });

        function calculateTotals() {
            let grossTotal = 0;
            $('.item-row').each(function () {
                let qty = parseFloat($(this).find('.CSquantity').val()) || 0;
                let up  = parseFloat($(this).find('.unitPrice').val()) || 0;
                let qpu = parseFloat($(this).find('.Quantinumber').val()) || 0;
                let tie = parseFloat($(this).find('.productSize').val()) || 0;

                let priceCalculated = qpu * tie * up;
                $(this).find('.totalPrice').text(priceCalculated.toFixed(2));

                let rowTotal = priceCalculated * qty;
                $(this).find('.row-total').text(rowTotal.toFixed(2));
                grossTotal += rowTotal;
            });

            let vatableSales = grossTotal / 1.12;
            let vatAmount    = grossTotal - vatableSales;

            $('#gross_total').text('₱' + grossTotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
            $('#vatable_sales').text('₱' + vatableSales.toLocaleString(undefined, { minimumFractionDigits: 2 }));
            $('#vat_amount').text('₱' + vatAmount.toLocaleString(undefined, { minimumFractionDigits: 2 }));
            $('#grand_total').text('₱' + grossTotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));

            $('#gross_total_raw').val(grossTotal.toFixed(2));
            $('#vat_amount_raw').val(vatAmount.toFixed(2));
            $('#grand_total_raw').val(grossTotal.toFixed(2));
        }

        toggleExpiryHeader();
    });
}