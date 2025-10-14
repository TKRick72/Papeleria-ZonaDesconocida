<template>
  <Bar :data="chartData" :options="chartOptions" />
</template>

<script setup>
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const chartData = {
  labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
  datasets: [
    {
      label: 'Ventas Mensuales',
      backgroundColor: '#4e73df',
      data: [12000, 19000, 3000, 5000, 2000, 3000, 7000, 8000, 9000, 10000, 11000, 15000]
    }
  ]
};

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false
};
</script>
