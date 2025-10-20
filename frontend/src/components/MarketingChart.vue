<template>
  <div class="chart-wrapper"><canvas id="marketingChartCanvas"></canvas></div>
</template>
<script setup>
import { computed } from 'vue';
import { useChart } from '@/composables/useChart';
const props = defineProps({ chartData: Object });
const chartDataRef = computed(() => props.chartData);
const defaultOptions = { responsive: true, maintainAspectRatio: false };
useChart('marketingChartCanvas', 'line', chartDataRef, defaultOptions);
</script>
<style scoped>
.chart-wrapper { height: 300px; }
</style>
