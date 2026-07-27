import ApexCharts from 'apexcharts';

const mergeOptions = (base, patch) => Object.entries(patch).reduce((result, [key, value]) => {
    result[key] = value && typeof value === 'object' && !Array.isArray(value)
        ? mergeOptions(result[key] ?? {}, value)
        : value;

    return result;
}, { ...base });

const chartDefaults = () => ({
    chart: {
        toolbar: { show: false },
        zoom: { enabled: false },
        selection: { enabled: false },
        parentHeightOffset: 0,
        animations: { enabled: true, easing: 'easeinout', speed: 420 },
        fontFamily: 'Manrope, Inter, sans-serif',
    },
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 3, lineCap: 'round' },
    grid: {
        borderColor: '#efe4dc',
        strokeDashArray: 3,
        xaxis: { lines: { show: false } },
    },
    legend: { show: false },
    markers: { size: 0 },
});

export const registerCharts = (Alpine) => {
    Alpine.data('shadcnChart', (payload = {}) => ({
        chart: null,
        payload,
        init() {
            this.chart = new ApexCharts(this.$refs.canvas, mergeOptions(chartDefaults(), this.payload));
            Promise.resolve(this.chart.render()).then(() => {
                this.$el.dataset.chartReady = 'true';
                this.$el.dispatchEvent(new CustomEvent('sadita-chart-ready'));
            });

            this.$el.addEventListener('sadita-chart-update', (event) => {
                const patch = event.detail ?? {};
                this.payload = mergeOptions(this.payload, patch);

                this.chart.updateOptions(mergeOptions(chartDefaults(), this.payload), false, true);
            });
        },
        destroy() {
            this.chart?.destroy();
        },
    }));
};
