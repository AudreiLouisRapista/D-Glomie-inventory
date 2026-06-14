        function initDailySales(routes) {
            $(document).ready(function() {

                // ==========================================
                // HELPER — Initialize Select2 AJAX per row
                // ==========================================
                function initSelect2Product($select) {
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
                            data: function(params) {
                                return {
                                    search: params.term
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.map(function(item) {
                                        return {
                                            id: item.inventory_id,
                                            text: item.product_name,
                                            remaining: item.inventory_remainingQty
                                        };
                                    })
                                };
                            },
                            cache: true
                        },
                        templateResult: function(item) {
                            if (item.loading) return item.text;
                            return $('<span>' + item.text + ' <small class="text-muted">(' + (item
                                .remaining || 0) + ' remaining)</small></span>');
                        },
                        templateSelection: function(item) {
                            return item.text || item.id;
                        }
                    });
                }

                // Init Select2 on first row on page load
                initSelect2Product($('.select2-product').first());


                // ==========================================
                // ADD ROW
                // ==========================================
                $('#addSaleRow').on('click', function() {
                    var newRow = `
                <tr class="sale-row">
                    <td>
                        <select name="inventory_id[]" class="form-control sale-product select2-product"
                            style="width: 100%;">
                        </select>
                    </td>
                    <td>
                        <input type="number" name="quantity_sold[]"
                            class="form-control sale-quantity" placeholder="0" min="1">
                    </td>
                    <td>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">₱</span>
                            </div>
                            <input type="number" name="total_amount[]"
                                class="form-control sale-amount" step="0.01" placeholder="0.00">
                        </div>
                    </td>
                    <td class="sale-row-total font-weight-bold text-primary">₱0.00</td>
                    <td>
                        <button type="button" class="btn btn-outline-danger btn-sm border-0 remove-sale-row">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
                    var $newRow = $(newRow);
                    $('#saleRows').append($newRow);
                    initSelect2Product($newRow.find('.select2-product'));
                });


                // ==========================================
                // REMOVE ROW
                // ==========================================
                $(document).on('click', '.remove-sale-row', function() {
                    if ($('.sale-row').length > 1) {
                        var $row = $(this).closest('.sale-row');
                        if ($row.find('.select2-product').hasClass('select2-hidden-accessible')) {
                            $row.find('.select2-product').select2('destroy');
                        }
                        $row.remove();
                        recalculate();
                    } else {
                        Swal.fire('Warning', 'At least one row is required.', 'warning');
                    }
                });


                // ==========================================
                // RECALCULATE
                // ==========================================
                function recalculate() {
                    var grandTotal = 0;
                    var totalQty = 0;
                    var totalItems = 0;

                    $('.sale-row').each(function() {
                        var qty = parseFloat($(this).find('.sale-quantity').val()) || 0;
                        var amount = parseFloat($(this).find('.sale-amount').val()) || 0;

                        $(this).find('.sale-row-total').text('₱' + amount.toFixed(2));

                        grandTotal += amount;
                        totalQty += qty;
                        if (qty > 0 || amount > 0) totalItems++;
                    });

                    $('#grand_total').text('₱' + grandTotal.toFixed(2));
                    $('#grand_total_raw').val(grandTotal.toFixed(2));
                    $('#total_quantity').text(totalQty);
                    $('#total_items').text(totalItems);
                }


                // ==========================================
                // LISTEN FOR INPUT
                // ==========================================
                $(document).on('input', '.sale-quantity, .sale-amount', function() {
                    recalculate();
                });


                // ==========================================
                // FORM SUBMIT
                // ==========================================
                $('#dailySalesForm').on('submit', function(e) {
                    e.preventDefault();

                    var hasEmptyProduct = false;
                    $('.sale-row').each(function() {
                        if (!$(this).find('.select2-product').val()) {
                            hasEmptyProduct = true;
                        }
                    });

                    if (hasEmptyProduct) {
                        Swal.fire('Incomplete', 'Please select a product for all rows.', 'warning');
                        return;
                    }

                    Swal.fire({
                        title: 'Save Daily Sales?',
                        text: 'Are you sure you want to save these sales records?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#1a56db',
                        confirmButtonText: 'Yes, Save it',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: routes.saveDailySalesUrl,
                                method: 'POST',
                                data: $(this).serialize(),
                                beforeSend: function() {
                                    Swal.fire({
                                        title: 'Saving...',
                                        allowOutsideClick: false,
                                        didOpen: () => {
                                            Swal.showLoading();
                                        }
                                    });
                                },
                                success: function(response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Saved!',
                                        text: response.save,
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(function() {
                                        // Reset form
                                        $('#dailySalesForm')[0].reset();

                                        // Reset to one empty row
                                        $('#saleRows .sale-row').each(function(
                                            i) {
                                            if (i > 0) {
                                                $(this).find(
                                                    '.select2-product'
                                                    ).select2(
                                                    'destroy');
                                                $(this).remove();
                                            }
                                        });

                                        // Reset first row Select2
                                        var $firstSelect = $(
                                                '#saleRows .select2-product')
                                            .first();
                                        if ($firstSelect.hasClass(
                                                'select2-hidden-accessible')) {
                                            $firstSelect.select2('destroy');
                                        }
                                        $firstSelect.val(null);
                                        initSelect2Product($firstSelect);

                                        recalculate();
                                    });
                                },
                                error: function(xhr) {
                                    var msg = xhr.responseJSON?.error ||
                                        xhr.responseJSON?.errorMessage ||
                                        'Something went wrong.';
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: msg
                                    });
                                }
                            });
                        }
                    });
                });

            });
        }