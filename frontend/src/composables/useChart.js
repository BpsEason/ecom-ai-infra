import { ref, onMounted, onUnmounted } from 'vue';
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);
export function useChart(chartId, type = 'bar', data, options) {
    const chartInstance = ref(null);
    onMounted(() => {
        const ctx = document.getElementById(chartId);
        if (ctx) {
            chartInstance.value = new Chart(ctx, { type, data: data.value, options: options || {} });
        }
    });
    onUnmounted(() => {
        if (chartInstance.value) chartInstance.value.destroy();
    });
    return { chartInstance };
}
