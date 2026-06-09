function initSalesHistory(routes) {
    $(document).ready(function () {

        // ==========================================
        // 1. DATATABLE
        // ==========================================
        $('#example2').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            searching: true,
            lengthChange: false,
            scrollX: false,
            autoWidth: false,
            ajax: {
                url: routes.viewSalesHistoryUrl,
            },
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden"></span></div>'
            },
            columns: [
                {
                    data: 'sale_ID',
                    name: 'inventorySales.id',
                    render: function (data) {
                        return '<span class="record-id">SALE-' + data + '</span>';
                    }
                },
                { data: 'product_name',  name: 'product.product_name' },
                { data: 'category_name', name: 'category.category_name' },
                {
                    data: 'selling_price',
                    name: 'inventory.inventory_sellingPrice',
                    render: function (data) {
                        return '₱' + parseFloat(data).toFixed(2);
                    }
                },
                { data: 'quantity_sold', name: 'inventory.quantity_sold' },
                {
                    data: 'total_amount',
                    name: 'inventorySales.total_amount',
                    render: function (data) {
                        return '₱' + parseFloat(data).toFixed(2);
                    }
                },
                { data: 'sale_date',     name: 'inventorySales.created_at' },
            ],
        });

    });
}