function initInventory(routes) {
    $(document).ready(function () {

        // ==========================================
        // 1. DATATABLE
        // ==========================================
        var table = $('#example2').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            searching: true,
            lengthChange: false,
            scrollX: true,        // ✅ enables horizontal scrolling
            autoWidth: false,   
            ajax: {
                url: routes.viewInventoryUrl,  
                data: function (d) {
                    d.category_id_table = $('#categorySelect').val();
                    d.product_id_table  = $('#productSelect').val();
                }
            },
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Processing...</span></div>'
            },
            columns: [
                {
                    data: 'inventory_ID',
                    name: 'inventory.id',
                    render: function (data) {
                        return '<span class="fw-bold text-secondary">INVT-' + data + '</span>';
                    }
                },
                { data: 'product_name',          name: 'product.product_name' },
                { data: 'name',                  name: 'category.category_name' },
                { data: 'unit_price',            name: 'purchase_items.unit_price' },
                { data: 'invt_sellingPrice',     name: 'inventory.inventory_sellingPrice' },
                { data: 'invt_StartingQuantity', name: 'inventory.inventory_startingQty' },
                { data: 'invt_NewQuantity',      name: 'inventory.inventory_newQty' },
                { data: 'invt_totalSold',        name: 'inventory.inventory_totalSold' },
                { data: 'invt_remainingStock',   name: 'invt_remainingStock', orderable: false },
                {
                    data: 'status_ID',
                    name: 'inventory.status_id',
                    render: function (data) {
                        if (data == 1) return '<span class="badge badge-success px-2 py-1">In Stock</span>';
                        if (data == 2) return '<span class="badge badge-warning px-2 py-1">Low Stock</span>';
                        if (data == 3) return '<span class="badge badge-danger px-2 py-1">Out of Stock</span>';
                        return '<span class="badge badge-secondary px-2 py-1">Unknown</span>';
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
        });


        // ==========================================
        // 2. SELECT2 INITIALIZATION
        // ==========================================
        $('#registerProductModal').on('shown.bs.modal', function () {
            $('#categorySelect').select2({
                dropdownParent: $('#registerProductModal'),
                placeholder: 'Select Category',
                allowClear: true,
                width: '100%'
            });
            $('#productSelect').select2({
                dropdownParent: $('#registerProductModal'),
                placeholder: '-- Select Category First --',
                allowClear: true,
                width: '100%'
            });
        });


        // ==========================================
        // 3. DYNAMIC PRODUCT DROPDOWN
        // ==========================================
        function populateProductsIntoSelect(productSelect, data) {
            productSelect.empty().append('<option value="">-- Select Product --</option>');

            if (data.length === 0) {
                productSelect.append('<option value="" disabled>No products in this category</option>');
                return;
            }

            $.each(data, function (key, value) {
                var qty        = parseInt(value.batch_quantity) || 0;
                var statusLabel = qty > 0 ? ` (${qty} available)` : ` (No stock)`;
                var isDisabled  = qty <= 0 ? 'disabled style="color:#adb5bd;"' : '';

                productSelect.append(
                    `<option value="${value.product_ID}"
                        data-unit-cost="${value.unit_cost}"
                        data-qty="${qty}"
                        ${isDisabled}>
                        ${value.product_name}${statusLabel}
                    </option>`
                );
            });

            productSelect.trigger('change.select2');
        }

        $('#categorySelect').on('change', function () {
            var categoryId    = $(this).val();
            var productSelect = $('#productSelect');

            productSelect.prop('disabled', true)
                         .empty()
                         .append('<option value="">Loading...</option>')
                         .trigger('change.select2');

            if (!categoryId) {
                productSelect.empty()
                             .append('<option value="">-- Select Category First --</option>')
                             .trigger('change.select2');
                return;
            }

            $.ajax({
                url: routes.getProductsUrl,  
                method: 'GET',
                data: { category_id: categoryId },
                success: function (response) {
                    productSelect.prop('disabled', false);
                    populateProductsIntoSelect(productSelect, response);
                },
                error: function () {
                    productSelect.prop('disabled', false)
                                 .empty()
                                 .append('<option value="">Failed to load products</option>')
                                 .trigger('change.select2');
                }
            });
        });

        // Auto-fill cost price when product is selected
        $('#productSelect').on('change', function () {
            var unitCost = $(this).find('option:selected').data('unit-cost') || '';
            $('#costPriceInput').val(unitCost);
        });


        // ==========================================
        // 4. FORM SUBMIT WITH SWAL CONFIRMATION
        // ==========================================
        $('#registerProductForm').on('submit', function (e) {
            e.preventDefault();

            var form         = $(this);
            var actionUrl    = routes.saveInventoryUrl;  
            var productName  = $('#productSelect').find('option:selected').text().split('(')[0].trim();
            var categoryName = $('#categorySelect').find('option:selected').text();
            var costPrice    = $('#costPriceInput').val();
            var sellingPrice = $('#sellingPriceInput').val();
            var quantity     = $('#quantityInput').val();

            if (!$('#categorySelect').val() || !$('#productSelect').val()) {
                Swal.fire('Incomplete', 'Please select a category and product.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Confirm Stock Entry',
                html: `
                    <div style="text-align:left; font-size:0.9rem; line-height:1.6;">
                        <div class="mb-2"><strong>Category:</strong> <span class="text-primary">${categoryName}</span></div>
                        <div class="mb-2"><strong>Product:</strong> ${productName}</div>
                        <hr>
                        <div class="row mx-0">
                            <div class="col-4 px-1"><strong>Cost:</strong> ₱${costPrice}</div>
                            <div class="col-4 px-1"><strong>Price:</strong> ₱${sellingPrice}</div>
                            <div class="col-4 px-1"><strong>Qty:</strong> ${quantity}</div>
                        </div>
                    </div>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                confirmButtonText: 'Confirm and Save',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: actionUrl,
                        method: 'POST',
                        data: form.serialize(),
                        beforeSend: function () {
                            Swal.fire({
                                title: 'Saving...',
                                allowOutsideClick: false,
                                didOpen: () => { Swal.showLoading(); }
                            });
                        },
                        success: function (response) {
                            $('#registerProductModal').modal('hide');
                            form[0].reset();
                            $('#categorySelect').val(null).trigger('change.select2');
                            $('#productSelect').prop('disabled', true)
                                              .empty()
                                              .append('<option value="">-- Select Category First --</option>')
                                              .trigger('change.select2');

                            Swal.fire({
                                icon: 'success',
                                title: 'Inventory Registered!',
                                text: response.save,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(function () {
                                table.draw();
                            });
                        },
                        error: function (xhr) {
                            var errors = xhr.responseJSON?.errors;
                            var msg    = errors
                                ? Object.values(errors).flat().join('<br>')
                                : (xhr.responseJSON?.error || 'Something went wrong.');
                            Swal.fire({ icon: 'error', title: 'Error', html: msg });
                        }
                    });
                }
            });
        });


        // ==========================================
        // 5. RESET MODAL ON CLOSE
        // ==========================================
        $('#registerProductModal').on('hidden.bs.modal', function () {
            $('#registerProductForm')[0].reset();
            $('#categorySelect').val(null).trigger('change.select2');
            $('#productSelect').prop('disabled', true)
                               .empty()
                               .append('<option value="">-- Select Category First --</option>')
                               .trigger('change.select2');
            $('#costPriceInput').val('');
        });

    });
}