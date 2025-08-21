<div
    class="w-full ">

    <span class="text-xl font-bold">Mes Atual</span>
    <div id="chart-filial-mensal"></div>
</div>
@script
<script>
    console.log('Chart Anual', @json($data));
    var data = @json($data);
    var seriesData = @json($data['series']);
    var categories = @json($data['categories']);

    function formatCurrency(val) {
        return val.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
    }

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
            formatter: formatCurrency,
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
                formatter: formatCurrency,
            }
        },
        xaxis: {
            categories: categories,

        },
        yaxis: {
            labels: {
                formatter: formatCurrency
            }
        },

    };

    var chart = new ApexCharts(document.querySelector("#chart-filial-mensal"), options);
    chart.render();
</script>

@endscript

