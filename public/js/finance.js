function initFinance(routes) {
    $(document).ready(function () {

        var lineChartInstance = null;
        var barChartInstance  = null;
        var currentGraphFilter = 'day';

        // ==========================================
        // 1. DATATABLE — DAILY TRANSACTIONS
        // ==========================================
        var table = $('#example2').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            searching: true,
            lengthChange: false,
            scrollX: false,
            autoWidth: false,
            ajax: {
                url: routes.viewFinanceUrl,
                data: function (d) {
                    d.month = $('#monthFilter').val();
                    d.year  = $('#yearFilter').val();
                }
            },
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden"></span></div>'
            },
            columns: [
                { data: 'report_date',    name: 'report_date' },
                { data: 'gross_sales',    name: 'gross_sales' },
                { data: 'total_expenses', name: 'total_expenses' },
                { data: 'net_income',     name: 'net_income' },
                { data: 'action',         name: 'action', orderable: false, searchable: false }
            ],
        });


        // ==========================================
        // 2. PAGE FILTER — Month/Year
        // ==========================================
        $('#applyPageFilter').on('click', function () {
            table.ajax.reload();
            loadSummaryCards();
            loadGraphData(currentGraphFilter);
        });


        // ==========================================
        // 3. LOAD SUMMARY CARDS (AJAX refresh on filter change)
        // ==========================================
        function loadSummaryCards() {
            $.ajax({
                url: window.location.pathname,
                method: 'GET',
                data: {
                    month: $('#monthFilter').val(),
                    year:  $('#yearFilter').val(),
                    ajax_summary: true
                },
                success: function (response) {
                    if (response.summary) {
                        $('#totalNetAmount').text('₱' + parseFloat(response.summary.total_net_amount).toFixed(2));
                        $('#totalRevenue').text('₱' + parseFloat(response.summary.total_revenue).toFixed(2));
                        $('#totalProfit').text('₱' + parseFloat(response.summary.total_profit).toFixed(2));
                        $('#totalExpenses').text('₱' + parseFloat(response.summary.total_expenses).toFixed(2));
                    }
                }
            });
        }


        // ==========================================
        // 4. GRAPH FILTER — Day / Week (independent of page filter)
        // ==========================================
        $('.graph-filter-btn').on('click', function () {
            $('.graph-filter-btn').removeClass('active');
            $(this).addClass('active');
            currentGraphFilter = $(this).data('filter');
            loadGraphData(currentGraphFilter);
        });


        // ==========================================
        // 5. LOAD GRAPH DATA
        // ==========================================
        function loadGraphData(filter) {
            $.ajax({
                url: routes.getGraphDataUrl,
                method: 'GET',
                data: {
                    month:  $('#monthFilter').val(),
                    year:   $('#yearFilter').val(),
                    filter: filter
                },
                success: function (response) {
                    renderLineChart(response.labels, response.net_income);
                    renderBarChart(response.labels, response.gross_sales, response.total_purchases);
                },
                error: function () {
                    console.error('Failed to load graph data.');
                }
            });
        }


        // ==========================================
        // 6. RENDER LINE CHART — Net Income Trend
        // ==========================================
        function renderLineChart(labels, netIncomeData) {
            var ctx = document.getElementById('line-chart');
            if (!ctx) return;

            if (lineChartInstance) {
                lineChartInstance.destroy();
            }

            lineChartInstance = new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Net Income',
                        fill: false,
                        borderWidth: 2,
                        lineTension: 0,
                        spanGaps: true,
                        borderColor: '#ffffff',
                        pointRadius: 3,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#ffffff',
                        data: netIncomeData
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: { display: false },
                    scales: {
                        xAxes: [{
                            ticks: { fontColor: '#efefef' },
                            gridLines: { display: true, color: 'rgba(255, 255, 255, 0.2)', drawBorder: false }
                        }],
                        yAxes: [{
                            ticks: {
                                fontColor: '#efefef',
                                callback: function (value) {
                                    return '₱' + (value >= 1000 ? (value / 1000) + 'k' : value);
                                }
                            },
                            gridLines: { display: true, color: 'rgba(255, 255, 255, 0.2)', drawBorder: false }
                        }]
                    }
                }
            });
        }


        // ==========================================
        // 7. RENDER BAR CHART — Purchases vs Sales
        // ==========================================
        function renderBarChart(labels, salesData, purchasesData) {
            var ctx = document.getElementById('sales-chart');
            if (!ctx) return;

            if (barChartInstance) {
                barChartInstance.destroy();
            }

            var ticksStyle = { fontColor: '#495057', fontStyle: 'bold' };

            barChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Sales',
                            backgroundColor: '#007bff',
                            borderColor: '#007bff',
                            data: salesData
                        },
                        {
                            label: 'Purchases',
                            backgroundColor: '#ced4da',
                            borderColor: '#ced4da',
                            data: purchasesData
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: { mode: 'index', intersect: true },
                    hover: { mode: 'index', intersect: true },
                    legend: { display: false },
                    scales: {
                        yAxes: [{
                            gridLines: { display: true, lineWidth: '1px', color: 'rgba(0, 0, 0, .1)', zeroLineColor: 'transparent' },
                            ticks: $.extend({
                                beginAtZero: true,
                                callback: function (value) {
                                    return '₱' + (value >= 1000 ? (value / 1000) + 'k' : value);
                                }
                            }, ticksStyle)
                        }],
                        xAxes: [{ display: true, gridLines: { display: false }, ticks: ticksStyle }]
                    }
                }
            });
        }


        // ==========================================
        // 8. VIEW REPORT DETAILS
        // ==========================================
        $('#example2 tbody').on('click', '.btn-view', function () {
            var id = $(this).data('id');

            $.ajax({
                url: routes.viewReportUrl + '/' + id,
                method: 'GET',
                success: function (response) {
                    var report = response.report;
                    var expensesHtml = '';

                    $.each(response.expenses, function (i, exp) {
                        expensesHtml += '<div class="d-flex justify-content-between mb-1">' +
                            '<span>' + exp.label + '</span>' +
                            '<span class="font-weight-bold">₱' + parseFloat(exp.amount).toFixed(2) + '</span>' +
                            '</div>';
                    });

                    Swal.fire({
                        title: 'Report Details',
                        html: `
                            <div style="text-align:left; font-size:0.9rem;">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>Date:</strong> <span>${report.report_date}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>Gross Sales:</strong> <span>₱${parseFloat(report.gross_sales).toFixed(2)}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>Total Expenses:</strong> <span>₱${parseFloat(report.total_expenses).toFixed(2)}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>Net Income:</strong> <span>₱${parseFloat(report.net_income).toFixed(2)}</span>
                                </div>
                                <hr>
                                <strong>Expenses Breakdown:</strong>
                                ${expensesHtml || '<p class="text-muted">No expenses recorded.</p>'}
                            </div>
                        `,
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#1a56db'
                    });
                },
                error: function () {
                    Swal.fire('Error', 'Failed to load report details.', 'error');
                }
            });
        });


        // ==========================================
        // INITIAL LOAD
        // ==========================================
        loadGraphData(currentGraphFilter);

    });
}