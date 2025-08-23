<div>


</div>
@script
    <script>
        (function() {
            var chartData = @json($data);
            console.log(chartData.series);
            var options = {
                series: [{
                    name: 'Total',
                    data: chartData.series
                }, {
                    name: 'Mês Anterior',
                    data: chartData.series_anterior
                }],
                colors: ['#002855', '#F9C408'],
                chart: {
                    height: 200,
                    type: 'area'
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return "R$ " + val;
                    }
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {

                    categories: chartData.categories

                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    },
                    y: {
                        formatter: function(val) {
                            return "R$ " + val;
                        }
                    }
                },
            };

            var chart = new ApexCharts(document.querySelector("#chart-quant"), options);
            chart.render();
        })();
    </script>
@endscript
