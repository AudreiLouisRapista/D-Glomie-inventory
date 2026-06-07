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
            scrollX: true,
            autoWidth: false,
            ajax: {
                url: routes.viewProductUrl,
                // data: function (d) {
                //     d.category_id_table = $('#categorySelect').val();
                //     d.product_id_table = $('#productSelect').val();
                // }
            },
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
                { data: 'product_name', name: 'product.product_name' },
                { data: 'category_name', name: 'category.category_name' },
                { data: 'perishable_type', name: 'perishable.perishable_type' },
                { data: 'bundle_quantity', name: 'product.bundle_quantity' },
                { data: 'bundle_size', name: 'product.bundle_size' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
        });
   
}