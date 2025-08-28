<div class="w-full ">

    <span class="text-xl font-bold">Anual</span>
    <div id="chart"></div>
</div>
@script
<script>
    (function () {
        //console.log('Chart Anual', @json($data));
        //var data = @json($data);
        //var seriesData = @json($data['series']);
        //var categories = @json($data['categories']);
        let options = {
            series: [{
                name: 'Total',
                data: [31, 40, 28, 51, 42, 109, 100, 31, 40, 28, 51, 42]
            }, {
                name: 'Metas',
                data: [11, 32, 45, 32, 34, 52, 41, 0, 0, 0, 0, 0]
            }],
            colors: ['#002855', '#F9C408'],
            chart: {
                height: 350,
                type: 'bar'
            },

            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez']
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
        };

        let chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    })();

</script>
@endscript
