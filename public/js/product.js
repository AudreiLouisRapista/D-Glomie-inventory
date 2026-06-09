function initProduct(routes) {

    // ==========================================
    // 1. DATATABLE
    // ==========================================
    var table = $('#example2').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        searching: true,
        lengthChange: false,
        // scrollX: true,
        autoWidth: false,
        ajax: { url: routes.viewProductUrl },
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden"></span></div>'
        },
        columns: [
            {
                data: 'product_ID',
                name: 'product.id',
                render: function (data) {
                    return '<span class="record-id">PROD-' + data + '</span>';
                }
            },
            { data: 'product_name',   name: 'product.product_name' },
            { data: 'category_name',  name: 'category.category_name' },
            { data: 'perishable_type', name: 'perishable.perishable_type' },
            { data: 'bundle_quantity', name: 'product.bundle_quantity' },
            { data: 'bundle_size',    name: 'product.bundle_size' },
            { data: 'action',         name: 'action', orderable: false, searchable: false }
        ],
    });


    // ==========================================
    // 2. EDIT BUTTON CLICK
    // ==========================================
    $('#example2 tbody').on('click', '.btn-edit', function () {
        var el = $(this);

        // Pre-fill all fields
        $('#edit_product_id').val(el.attr('data-id'));
        $('#edit_product_name').val(el.attr('data-product-name'));
        $('#edit_bundle_quantity').val(el.attr('data-bundle-quantity'));
        $('#edit_bundle_size').val(el.attr('data-bundle-size'));

        // Pre-select category
        var $categorySelect = $('#edit_category');
        if ($categorySelect.hasClass('select2-hidden-accessible')) {
            $categorySelect.select2('destroy');
        }
        $categorySelect.val(el.attr('data-category-id')).select2({
            dropdownParent: $('#updateProductModal'),
            placeholder: 'Select Category',
            allowClear: true,
            width: '100%'
        });

        // Pre-select perishable type
        $('#edit_perishable_type').val(el.attr('data-perishable-id'));

        $('#updateProductModal').modal('show');
    });


    // ==========================================
    // 3. UPDATE FORM SUBMIT
    // ==========================================
    $('#updateProductForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            beforeSend: function () {
                Swal.fire({
                    title: 'Updating...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
            },
            success: function (response) {
                $('#updateProductModal').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: response.save,
                    timer: 2000,
                    showConfirmButton: false
                }).then(function () {
                    table.ajax.reload(null, false);
                });
            },
            error: function (xhr) {
                var errors = xhr.responseJSON?.errors;
                var msg    = errors
                    ? Object.values(errors).flat().join('<br>')
                    : (xhr.responseJSON?.errorMessage || 'Something went wrong.');
                Swal.fire({ icon: 'error', title: 'Error', html: msg });
            }
        });
    });


    // ==========================================
    // 4. RESET MODAL ON CLOSE
    // ==========================================
    $('#updateProductModal').on('hidden.bs.modal', function () {
        $(this).attr('aria-hidden', 'true');

        if ($('#edit_category').hasClass('select2-hidden-accessible')) {
            $('#edit_category').select2('destroy');
        }

        $(this).removeData();
        $('#updateProductForm')[0].reset();
    });

    // ==========================================
    // 5. SOFT DELETE
    // ==========================================
    $('#example2 tbody').on('click', '.btn-delete', function () {
        var id = $(this).data('id');

        Swal.fire({
            title: 'Archive this record?',
            text: 'This will move the product record to archives.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, Archive it',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: routes.softDeleteProductUrl + '/' + id,
                    method: 'POST',
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Archiving...',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Archived!',
                            text: response.save,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(function () {
                            table.ajax.reload(null, false);
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: xhr.responseJSON?.message || 'Something went wrong.'
                        });
                    }
                });
            }
        });
    });

}