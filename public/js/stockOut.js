function initStockTransfer(routes) {
    $(document).ready(function () {

        // ==========================================
        // HELPER — Initialize Select2 AJAX per row
        // ==========================================
        function initSelect2Transfer($select) {
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }
            $select.select2({
                placeholder: 'Search product...',
                allowClear: true,
                width: '100%',
                minimumInputLength: 1,
                ajax: {
                    url: routes.getProductsUrl,
                    method: 'GET',
                    delay: 300,
                    data: function (params) {
                        return { search: params.term };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function (item) {
                                return {
                                    id:        item.inventory_id,
                                    text:      item.product_name,
                                    unitPrice: item.unit_price,
                                    remaining: item.inventory_remainingQty
                                };
                            })
                        };
                    },
                    cache: true
                },
                templateResult: function (item) {
                    if (item.loading) return item.text;
                    return $(
                        '<span>' + item.text +
                        ' <small class="text-muted">(' + (item.remaining || 0) + ' remaining)</small></span>'
                    );
                },
                templateSelection: function (item) {
                    return item.text || item.id;
                }
            });
        }

        // Init Select2 on first row
        initSelect2Transfer($('.select2-transfer').first());

        // ==========================================
        // PRODUCT SELECTED — auto-fill unit price
        // ==========================================
        $(document).on('select2:select', '.select2-transfer', function (e) {
            var data     = e.params.data;
            var $row     = $(this).closest('.transfer-row');
            var unitPrice = parseFloat(data.unitPrice) || 0;

            $row.find('.transfer-unit-price').val(unitPrice.toFixed(2));
            $row.attr('data-unit-price', unitPrice);

            // Recalculate row total if quantity already entered
            var qty = parseFloat($row.find('.transfer-quantity').val()) || 0;
            if (qty > 0) {
                $row.find('.transfer-amount').val((unitPrice * qty).toFixed(2));
            }

            recalculate();
        });

        // ==========================================
        // QUANTITY INPUT — auto-calculate amount
        // ==========================================
        $(document).on('input', '.transfer-quantity', function () {
            var $row      = $(this).closest('.transfer-row');
            var qty       = parseFloat($(this).val()) || 0;
            var unitPrice = parseFloat($row.attr('data-unit-price')) || 0;
            var amount    = qty * unitPrice;

            $row.find('.transfer-amount').val(amount > 0 ? amount.toFixed(2) : '');
            recalculate();
        });

        // ==========================================
        // ADD ROW
        // ==========================================
        $('#addTransferRow').on('click', function () {
            var newRow = `
                <tr class="transfer-row">
                    <td>
                        <select name="inventory_id[]"
                            class="form-control transfer-product select2-transfer"
                            style="width: 100%;">
                        </select>
                    </td>
                    <td>
                        <input type="number" name="quantity[]"
                            class="form-control transfer-quantity"
                            placeholder="0" min="1">
                    </td>
                    <td>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">₱</span>
                            </div>
                            <input type="number" name="unit_price[]"
                                class="form-control transfer-unit-price bg-light"
                                step="0.01" placeholder="0.00" readonly>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">₱</span>
                            </div>
                            <input type="number" name="amount[]"
                                class="form-control transfer-amount bg-light"
                                step="0.01" placeholder="0.00" readonly>
                        </div>
                    </td>
                    <td>
                        <button type="button"
                            class="btn btn-outline-danger btn-sm border-0 remove-transfer-row">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
            var $newRow = $(newRow);
            $('#transferRows').append($newRow);
            initSelect2Transfer($newRow.find('.select2-transfer'));
        });

        // ==========================================
        // REMOVE ROW
        // ==========================================
        $(document).on('click', '.remove-transfer-row', function () {
            if ($('.transfer-row').length > 1) {
                var $row = $(this).closest('.transfer-row');
                if ($row.find('.select2-transfer').hasClass('select2-hidden-accessible')) {
                    $row.find('.select2-transfer').select2('destroy');
                }
                $row.remove();
                recalculate();
            } else {
                Swal.fire('Warning', 'At least one item is required.', 'warning');
            }
        });

        // ==========================================
        // RECALCULATE SUMMARY
        // ==========================================
        function recalculate() {
            var grandTotal = 0;
            var totalQty   = 0;
            var totalItems = 0;

            $('.transfer-row').each(function () {
                var qty    = parseFloat($(this).find('.transfer-quantity').val()) || 0;
                var amount = parseFloat($(this).find('.transfer-amount').val()) || 0;

                grandTotal += amount;
                totalQty   += qty;
                if (qty > 0 || amount > 0) totalItems++;
            });

            $('#grand_total').text('₱' + grandTotal.toFixed(2));
            $('#grand_total_raw').val(grandTotal.toFixed(2));
            $('#total_quantity').text(totalQty);
            $('#total_items').text(totalItems);
        }

        // ==========================================
        // FORM SUBMIT
        // ==========================================
        $('#stockTransferForm').on('submit', function (e) {
            e.preventDefault();

            if (!$('#to_branch_id').val()) {
                Swal.fire('Incomplete', 'Please select a destination branch.', 'warning');
                return;
            }

            var hasEmptyProduct = false;
            $('.transfer-row').each(function () {
                if (!$(this).find('.select2-transfer').val()) {
                    hasEmptyProduct = true;
                }
            });

            if (hasEmptyProduct) {
                Swal.fire('Incomplete', 'Please select a product for all rows.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Save Stock Transfer?',
                text: 'Are you sure you want to transfer this stock?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1a56db',
                confirmButtonText: 'Yes, Transfer it',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: routes.saveStockTransferUrl,
                        method: 'POST',
                        data: $(this).serialize(),
                        beforeSend: function () {
                            Swal.fire({
                                title: 'Saving...',
                                allowOutsideClick: false,
                                didOpen: () => { Swal.showLoading(); }
                            });
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Transferred!',
                                text: response.save,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(function () {
                                // Reset form
                                $('#stockTransferForm')[0].reset();

                                // Reset to one empty row
                                $('.transfer-row').each(function (i) {
                                    if (i > 0) {
                                        $(this).find('.select2-transfer').select2('destroy');
                                        $(this).remove();
                                    }
                                });

                                // Reset first row
                                var $firstSelect = $('.select2-transfer').first();
                                if ($firstSelect.hasClass('select2-hidden-accessible')) {
                                    $firstSelect.select2('destroy');
                                }
                                $firstSelect.val(null);
                                initSelect2Transfer($firstSelect);

                                $('.transfer-unit-price').val('');
                                $('.transfer-amount').val('');
                                recalculate();
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

    });
}