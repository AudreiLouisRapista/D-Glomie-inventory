function initPaymentTracker(allProducts) {
    $(document).ready(function () {

        // 1. Initialize DataTable
        var table = $('#example2').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "responsive": true,
            order: [
                [0, 'desc']
            ],
            "destroy": true,
        });


        // view item button
            $(document).on('click', '.btn-view', function(e) {
                e.stopPropagation();
                var target = $(this).data('bs-target');
                $(target).modal('show');
            });
        
        // 2. Pay Button Handler
     
        $(document).on('click', '.pay-btn', function (e) {
            // Prevent double triggering if clicked inside a footer
            if ($(this).closest('.modal-footer').length > 0) return;

            let id      = $(this).data('id');
            let invoice = $(this).data('invoice');
            let balance = $(this).data('remaining'); // Current unpaid amount
            let net     = $(this).data('net');       // Total invoice amount

            // Set hidden inputs for the form
            $('#modal_purchase_id').val(id);
            $('#old_remaining_balance').val(balance); // FIXED: Use balance here, not net
            
            // Set visual text in the modal
            $('#modalInvoiceNumber').text(invoice);

            // Format the balance for the UI
            let formattedBalance = parseFloat(balance).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            $('#modalRemainingBalance').text('₱' + formattedBalance);
            
            // Auto-fill the payment amount with the remaining balance for convenience
            $('#amountPaid').val(parseFloat(balance).toFixed(2));

            // Open the modal
            $('#paymentModal').modal('show');
        });

        // 3. Supplier Filter
        $('#filterSupplier').on('change', function () {
            table.column(1).search(this.value).draw();
        });

        // 4. Payment History Modal — load data + pre-fill print receipt
        $('#paymentHistoryModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var invoice = button.data('invoice');
            var purchaseId = button.data('id');

            // Reset modal
            $('#modal-invoice-no').text(invoice);
            $('#modal-total-paid').text('₱ 0.00');
            $('#payment-history-data').html(
                '<tr><td colspan="4" class="text-center py-3"><div class="spinner-border spinner-border-sm text-success me-2"></div>Loading...</td></tr>'
            );

            // Pre-fill print header
            $('#print-invoice-no').text(invoice);
            $('#print-date').text(
                new Date().toLocaleDateString('en-PH', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }) +
                ' ' + new Date().toLocaleTimeString('en-PH', {
                    hour: '2-digit',
                    minute: '2-digit'
                })
            );

            $.ajax({
                url: '/Admin/getPaymentHistory/' + purchaseId,
                method: 'GET',
                success: function (response) {
                    let modalRows = '';
                    let printRows = '';
                    let total = 0;

                    if (response.length === 0) {
                        modalRows =
                            '<tr><td colspan="4" class="text-center text-muted py-3">No payments found.</td></tr>';
                        printRows =
                            '<tr><td colspan="4" style="text-align:center;color:#6c757d;padding:12px 0;">No payments found.</td></tr>';
                    } else {
                        response.forEach(function (payment, index) {
                            let amt = parseFloat(payment.amount_paid) || 0;
                            total += amt;
                            let fmtAmt = amt.toLocaleString(undefined, {
                                minimumFractionDigits: 2
                            });
                            let refNo = payment.reference_number ?? 'N/A';
                            let method = payment.payment_method;
                            let date = payment.payment_date;

                            // Modal row
                            modalRows += `
                                    <tr>
                                        <td class="small text-muted">${date}</td>
                                        <td class="fw-semibold">${refNo}</td>
                                        <td><span class="badge bg-light text-dark border">${method}</span></td>
                                        <td class="text-end fw-bold">₱ ${fmtAmt}</td>
                                    </tr>`;

                            // Print row (inline styles for print reliability)
                          printRows += `
                            <tr style="border-bottom:1px solid #eef0f3; background:${index % 2 === 0 ? '#fff' : '#fafbfc'};">
                                <td style="padding:11px 10px 11px 0; color:#6c757d; font-size:11.5px; white-space:nowrap;">${date}</td>
                                <td style="padding:11px 10px 11px 0; font-family:'DM Mono',monospace; font-weight:600; font-size:11.5px; color:#1a1a2e; letter-spacing:0.3px;">${refNo}</td>
                                <td style="padding:11px 10px 11px 0;">
                                    <span style="display:inline-block; padding:3px 10px; background:#f0f4ff; border:1px solid #d0d9f5; border-radius:20px; font-size:10.5px; font-weight:600; color:#3d5af1; letter-spacing:0.3px; text-transform:uppercase;">
                                        ${method}
                                    </span>
                                </td>
                                <td style="padding:11px 0; text-align:right; font-weight:700; color:#1a1a2e; font-size:13px; font-family:'DM Mono',monospace;">₱&nbsp;${fmtAmt}</td>
                            </tr>`;
                        });
                    }

                    let fmtTotal = total.toLocaleString(undefined, {
                        minimumFractionDigits: 2
                    });

                    // Update modal
                    $('#payment-history-data').html(modalRows);
                    $('#modal-total-paid').text('₱ ' + fmtTotal);

                    // Update print receipt
                    $('#print-payment-rows').html(printRows);
                    $('#print-total-paid').text('₱ ' + fmtTotal);
                    $('#print-total-paid-2').text('₱ ' + fmtTotal);
                },
                error: function () {
                    $('#payment-history-data').html(
                        '<tr><td colspan="4" class="text-center text-danger py-3">Error loading data.</td></tr>'
                    );
                }
            });
        });

        // 5. Print Receipt Button — open in new blank window so ONLY receipt prints
        $('#printReceiptBtn').on('click', function () {
            var receiptHTML = $('#print-receipt-area').html();
            var printWindow = window.open('', '_blank', 'width=600,height=800');
            printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="UTF-8">
                        <title>Payment Receipt</title>
                        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@500&display=swap" rel="stylesheet">
                        <style>
                            @page { size: A5 portrait; margin: 10mm; }
                            * { box-sizing: border-box; margin: 0; padding: 0; }
                            body { font-family: 'DM Sans', sans-serif; background: #fff; }
                        </style>
                    </head>
                    <body>` + receiptHTML + `</body>
                    </html>
                `);
            printWindow.document.close();
            printWindow.focus();
            // Wait for fonts/images to load then print
            printWindow.onload = function () {
                printWindow.print();
                printWindow.close();
            };
        });

    });
}