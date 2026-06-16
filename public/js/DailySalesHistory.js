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
                    name: 'daily_sales.id',
                    render: function (data) {
                        return '<span class="record-id">SALE-' + data + '</span>';
                    }
                },
                { data: 'quantity_sold',  name: 'daily_sales.quantity_sold' },
                { data: 'total_amount', name: 'daily_sales.total_amount' },
                { data: 'sale_date',     name: 'daily_sales.sale_date' },
            ],
        });

    });
}