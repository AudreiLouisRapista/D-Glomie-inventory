function initProductArchive(routes) {
    var table = $('#example2').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: { url: routes.viewProductArchiveUrl },
        columns: [
            {
                data: 'product_ID',
                name: 'product.id',
                render: function(data) {
                    return '<span class="record-id">PROD-' + data + '</span>';
                }
            },
            { data: 'product_name',    name: 'product.product_name' },
            { data: 'category_name', name: 'category.category_name' },
            { data: 'deleted_at',      name: 'product.deleted_at' },
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
                    url: routes.restoreProductUrl + '/' + id,
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
                    url: routes.forceDeleteProductUrl + '/' + id,
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