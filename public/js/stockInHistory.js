function initPaymentTracker(allProducts) {
    $(document).ready(function () {

        // 1. Initialize DataTable
        var table = $('#example2').DataTable({
        "destroy": true,
        "processing": true,
        "searching": true,
        "lengthChange": false,
        });


        // view item button
            $(document).on('click', '.btn-view', function(e) {
                e.stopPropagation();
                var target = $(this).data('bs-target');
                $(target).modal('show');
            });
        


        // 3. Supplier Filter
        $('#filterSupplier').on('change', function () {
            table.column(1).search(this.value).draw();
        });


    });
}