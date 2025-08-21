<div
    class="w-full ">

    <span class="text-xl font-bold">Anual</span>
    <div id="chart-filial-anual"></div>
</div>
@script
<script>
    console.log('Chart Anual', @json($data));
    var data = @json($data);
    var seriesData = @json($data['series']);
    var categories = @json($data['categories']);
    var options = {
        series: seriesData,

        chart: {
            type: 'bar',
            height: 430
        },
        plotOptions: {
            bar: {

                dataLabels: {
                    position: 'top',
                },

            }
        },
        dataLabels: {
            enabled: false,
            offsetX: -6,
            style: {
                fontSize: '12px',
                colors: ['#fff']
            },
            formatter: function (val) {

                return val.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
            },
        },
        stroke: {
            show: true,
            width: 1,
            colors: ['#fff']
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (val) {
                    return val.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
                }
            }
        },
        xaxis: {
            categories: categories,
        },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return val.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
                }
            }
        },

    };

    var chart = new ApexCharts(document.querySelector("#chart-filial-anual"), options);
    chart.render();
</script>

@endscript

