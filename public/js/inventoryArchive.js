function initInventoryArchive(routes) {
    var table = $('#example2').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: { url: routes.viewInventoryArchiveUrl },
        columns: [
            {
                data: 'inventory_ID',
                name: 'inventory.id',
                render: function(data) {
                    return '<span class="record-id">INVT-' + data + '</span>';
                }
            },
            { data: 'product_name',    name: 'product.product_name' },
            { data: 'category_name', name: 'category.category_name' },
            { data: 'unit_price',            name: 'purchase_items.unit_price', searchable: false, orderable: false  },
            { data: 'invt_sellingPrice', name: 'inventory.inventory_sellingPrice' },
            { data: 'invt_startingQuantity', name: 'inventory.inventory_startingQuantity' },
            { data: 'invt_newQuantity', name: 'inventory.inventory_newQuantity' },
            { data: 'total_sold', name: 'inventory.inventory_totalSold' },
            { data: 'remaining_stock', name: 'inventory.inventory_remainingQty' },
            { data: 'deleted_at',      name: 'inventory.deleted_at' },
            { data: 'action',          name: 'action', orderable: false, searchable: false }
        ]
    });

    // Restore
    $('#example2 tbody').on('click', '.btn-restore', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Restore this record?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Restore',
            confirmButtonColor: '#0d6efd'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: routes.restoreInventoryUrl + '/' + id,
                    method: 'POST',
                    data: { id: id },
                    success: function(response) {
                        Swal.fire({ icon: 'success', title: response.save, timer: 2000, showConfirmButton: false });
                        table.ajax.reload(null, false);
                    }
                });
            }
        });
    });

    // Permanently delete
    $('#example2 tbody').on('click', '.btn-force-delete', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Permanently delete?',
            text: 'This cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete Forever',
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: routes.forceDeleteInventoryUrl + '/' + id,
                    method: 'DELETE',
                   data: { id: id },
                    success: function(response) {
                        Swal.fire({ icon: 'success', title: response.save, timer: 2000, showConfirmButton: false });
                        table.ajax.reload(null, false);
                    }
                });
            }
        });
    });
}