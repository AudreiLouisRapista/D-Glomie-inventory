
function initDailySales(routes) {
    $(document).ready(function() {

        // =========================================================================
        // PROCESS 3: Select2 Dynamic Dynamic Row Dropdown Configurator & Event Binder
        // =========================================================================
        function initSelect2Product($select) {
            // Process 3a: Avoid instance duplication by destroying existing Select2 attachments
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
                        return { search: params.term };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.inventory_id,
                                    text: item.product_name,
                                    remaining: item.inventory_remainingQty,
                                    selling_price: item.inventory_sellingPrice // Injected payload variable
                                };
                            })
                        };
                    },
                    cache: true
                },
                templateResult: function(item) {
                    if (item.loading) return item.text;
                    return $('<span>' + item.text + ' <small class="text-muted">(' + (item.remaining || 0) + ' remaining)</small></span>');
                },
                templateSelection: function(item) {
                    return item.text || item.id;
                }
            });

            // Process 3c: Dropdown Target Match & Selling Price Mapping Auto-Fill
            $select.on('select2:select', function(e) {
                var data = e.params.data;
                var $row = $(this).closest('tr');
                
                $row.find('.selling-price').val(data.selling_price || 0);
                calculateRowAmount($row);
            });

            // Process 3d: Row Variable Cleaner on Item Unselection
            $select.on('select2:clear', function() {
                var $row = $(this).closest('tr');
                $row.find('.selling-price').val('');
                $row.find('.sale-quantity').val('');
                $row.find('.sale-amount').val('');
                recalculate();
            });
        }

        initSelect2Product($('.select2-product').first());

        $('#addSaleRow').on('click', function() {
            var newRow = `
            <tr class="sale-row">
                <td>
                    <select name="inventory_id[]" class="form-control sale-product select2-product" style="width: 100%;">
                    </select>
                </td>
                <td>
                    <input type="number" name="selling_price[]" class="form-control selling-price" placeholder="0.00" min="0" step="0.01">
                </td>
                <td>
                    <input type="number" name="quantity_sold[]" class="form-control sale-quantity" placeholder="0" min="1">
                </td>
                <td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">₱</span>
                        </div>
                        <input type="number" name="total_amount[]" class="form-control sale-amount" step="0.01" placeholder="0.00" readonly>
                    </div>
                </td>
                <td class="sale-row-total font-weight-bold text-primary">₱0.00</td>
                <td>
                    <button type="button" class="btn btn-outline-danger btn-sm border-0 remove-sale-row">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>`;
            
            var $newRow = $(newRow);
            $('#saleRows').append($newRow);
            initSelect2Product($newRow.find('.select2-product'));
        });


        // =========================================================================
        // PROCESS: Dynamic Object Destructor Lifecycle Handler (Remove Item Click Event)
        // Destroys references properly to prevent structural memory leaks in JavaScript.
        // =========================================================================
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


        // =========================================================================
        // PROCESS: Isolated Single-Row Total Matrix Calculator
        // Evaluates isolated algebraic products per transaction index block.
        // =========================================================================
        function calculateRowAmount($row) {
            var price = parseFloat($row.find('.selling-price').val()) || 0;
            var qty = parseFloat($row.find('.sale-quantity').val()) || 0;
            var total = price * qty;

            $row.find('.sale-amount').val(total.toFixed(2));
            $row.find('.sale-row-total').text('₱' + total.toFixed(2));

            recalculate();
        }


        // =========================================================================
        // PROCESS: Multi-Row Aggregate Calculator (Grand Total Processor)
        // Traverses the entire active grid arrays to synthesize overall transactional summaries.
        // =========================================================================
        function recalculate() {
            var grandTotal = 0;
            var totalQty = 0;
            var totalItems = 0;

            $('.sale-row').each(function() {
                var qty = parseFloat($(this).find('.sale-quantity').val()) || 0;
                var amount = parseFloat($(this).find('.sale-amount').val()) || 0;

                grandTotal += amount;
                totalQty += qty;
                if (qty > 0 || amount > 0) totalItems++;
            });

            $('#grand_total').text('₱' + grandTotal.toFixed(2));
            $('#grand_total_raw').val(grandTotal.toFixed(2));
            $('#total_quantity').text(totalQty);
            $('#total_items').text(totalItems);
        }


        // =========================================================================
        // PROCESS: Manual User Manipulation Global Event Pipeline Listeners
        // Captures real-time key/numeric alterations to initiate recalculation loops.
        // =========================================================================
        $(document).on('input change', '.sale-quantity, .selling-price', function() {
            var $row = $(this).closest('tr');
            calculateRowAmount($row);
        });


        // =========================================================================
        // PROCESS: Form Interception, Data Validation, and Secure Serialization Submitter
        // Runs final integrity checks, confirms intents, and updates the application database via AJAX.
        // =========================================================================
        $('#dailySalesForm').on('submit', function(e) {
            e.preventDefault();

            // Process a: Structural Integrity Validator Loop
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

            // Process b: SweetAlert Submission Authorization Confirm Modal Window
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
                    // Process c: Secure Asynchronous HTTP Server Handshake Post Operation
                    $.ajax({
                        url: routes.saveDailySalesUrl,
                        method: 'POST',
                        data: $(this).serialize(),
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Saving...',
                                allowOutsideClick: false,
                                didOpen: () => { Swal.showLoading(); }
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
                                // Process d: Form Interface Clear & Component Reconstruction Lifecycle
                                $('#dailySalesForm')[0].reset();

                                $('#saleRows .sale-row').each(function(i) {
                                    if (i > 0) {
                                        $(this).find('.select2-product').select2('destroy');
                                        $(this).remove();
                                    }
                                });

                                var $firstSelect = $('#saleRows .select2-product').first();
                                if ($firstSelect.hasClass('select2-hidden-accessible')) {
                                    $firstSelect.select2('destroy');
                                }
                                $firstSelect.val(null);
                                initSelect2Product($firstSelect);

                                recalculate();
                            });
                        },
                        error: function(xhr) {
                            var msg = xhr.responseJSON?.error || xhr.responseJSON?.errorMessage || 'Something went wrong.';
                            Swal.fire({ icon: 'error', title: 'Error', text: msg });
                        }
                    });
                }
            });
        });

    });
}