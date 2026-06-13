function initFinance(routes) {
    $(document).ready(function () {
        
        // Initialize jQuery Knob widgets safely if plugin is available
        if ($.fn && $.fn.knob) {
            $('.knob').knob();
        }



        // BAR CHART: Only initializes if present in DOM
        if ($('#barChart').length) {
            var barChartCanvas = document.getElementById('barChart').getContext('2d');
            var barChartData = {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                        label: 'Electronics',
                        backgroundColor: 'rgba(210, 214, 222, 1)',
                        borderColor: 'rgba(210, 214, 222, 1)',
                        pointRadius: false,
                        pointColor: 'rgba(210,214,222,1)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data: [65, 59, 80, 81, 56, 55, 40]
                    },
                    {
                        label: 'Digital Goods',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: [28, 48, 40, 19, 86, 27, 90]
                    }
                ]
            };

            new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartData,
                options: { responsive: true, maintainAspectRatio: false, datasetFill: false }
            });
        }

        // DONUT CHART: Only initializes if present in DOM
        if ($('#sales-chart-canvas').length) {
            var pieChartCanvas = $('#sales-chart-canvas').get(0).getContext('2d');
            var pieData = {
                labels: ['Instore Sales', 'Download Sales', 'Mail-Order Sales'],
                datasets: [{
                    data: [30, 12, 20],
                    backgroundColor: ['#f56954', '#00a65a', '#f39c12']
                }]
            };
            new Chart(pieChartCanvas, {
                type: 'doughnut',
                data: pieData,
                options: { legend: { display: false }, maintainAspectRatio: false, responsive: true }
            });
        }

        // SALES OVER TIME BAR CHART
        if ($('#sales-chart').length) {
            var ticksStyle = { fontColor: '#495057', fontStyle: 'bold' };
            new Chart($('#sales-chart'), {
                type: 'bar',
                data: {
                    labels: ['JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
                    datasets: [
                        { backgroundColor: '#007bff', borderColor: '#007bff', data: [1000, 2000, 3000, 2500, 2700, 2500, 3000] },
                        { backgroundColor: '#ced4da', borderColor: '#ced4da', data: [700, 1700, 2700, 2000, 1800, 1500, 2000] }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: { mode: 'index', intersect: true },
                    hover: { mode: 'index', intersect: true },
                    legend: { display: false },
                    scales: {
                        yAxes: [{
                            gridLines: { display: true, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent' },
                            ticks: $.extend({
                                beginAtZero: true,
                                callback: function (value) { return '$' + (value >= 1000 ? (value / 1000) + 'k' : value); }
                            }, ticksStyle)
                        }],
                        xAxes: [{ display: true, gridLines: { display: false }, ticks: ticksStyle }]
                    }
                }
            });
        }

        // SALES GRAPH (White Line Chart inside dynamic background card)
        if ($('#line-chart').length) {
            var lineChartCanvas = document.getElementById('line-chart').getContext('2d');
            var lineChartData = {
                labels: ['2011 Q1', '2011 Q2', '2011 Q3', '2011 Q4', '2012 Q1', '2012 Q2', '2012 Q3', '2012 Q4', '2013 Q1', '2013 Q2'],
                datasets: [{
                    label: 'Sales Trend',
                    fill: false,
                    borderWidth: 2,
                    lineTension: 0,
                    spanGaps: true,
                    borderColor: '#efefef',
                    pointRadius: 3,
                    pointHoverRadius: 7,
                    pointColor: '#efefef',
                    pointBackgroundColor: '#efefef',
                    data: [2666, 2778, 4912, 3767, 6810, 5670, 4820, 15073, 10687, 8432]
                }]
            };

            var lineChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: { display: false },
                scales: {
                    xAxes: [{
                        ticks: { fontColor: '#efefef' },
                        gridLines: { display: true, color: 'rgba(255, 255, 255, 0.2)', drawBorder: false }
                    }],
                    yAxes: [{
                        ticks: { fontColor: '#efefef' },
                        gridLines: { display: true, color: 'rgba(255, 255, 255, 0.2)', drawBorder: false }
                    }]
                }
            };

            new Chart(lineChartCanvas, {
                type: 'line',
                data: lineChartData,
                options: lineChartOptions
            });
        }

        
    });
}