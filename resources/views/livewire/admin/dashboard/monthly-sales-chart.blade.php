<div class="card">
    <div class="card-header">
        <h4>Grafik Penjualan Per Bulan</h4>
    </div>
    <div class="card-body">
        <div id="chart-monthly-sales"></div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        const chart = new ApexCharts(document.getElementById("chart-monthly-sales"), {
            series: [{
                name: 'Penjualan',
                data: @json($salesData)
            }],
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },
            title: {
                text: 'Penjualan 12 Bulan Terakhir',
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'],
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: @json($months)
            }
        });
        
        chart.render();
    });
</script>
@endpush