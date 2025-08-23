<div>


</div>
@script
    <script>
        (function() {
            var chartData = @json($data);
            //console.log(chartData.series);
            console.log(chartData);
            var options = {
                series: [{
                    name: 'Total',
                    data: chartData.series
                }, {
                    name: 'Mês Anterior',
                    data: chartData.series_anterior
                }],
                chart: {
                    height: 300,
                    type: 'bar'
                },
                colors: ['#002855', '#F9C408'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',

                },
                xaxis: {

                    categories: chartData.categories,
                    labels: {
                        show: true,
                        rotate: -45
                    }


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

            var chart = new ApexCharts(document.querySelector("#chart-filial"), options);
            chart.render();
        })();
    </script>
@endscript
