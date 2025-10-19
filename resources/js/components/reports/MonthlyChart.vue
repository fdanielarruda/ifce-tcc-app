<script setup lang="ts">
import { computed } from "vue";
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend,
} from "chart.js";
import { Bar } from "vue-chartjs";

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

interface MonthlyData {
    month: string;
    receita: number;
    despesa: number;
}

interface Props {
    monthlyData: MonthlyData[];
}

const props = defineProps<Props>();

const chartData = computed(() => ({
    labels: props.monthlyData.map((item) => item.month),
    datasets: [
        {
            label: "Receitas",
            data: props.monthlyData.map((item) => item.receita),
            backgroundColor: "#10B981",
        },
        {
            label: "Despesas",
            data: props.monthlyData.map((item) => item.despesa),
            backgroundColor: "#EF4444",
        },
    ],
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: "top" as const,
            labels: {
                color: "#9CA3AF",
            },
        },
        tooltip: {
            callbacks: {
                label: function (context: any) {
                    return `${context.dataset.label}: R$ ${context.parsed.y.toFixed(2)}`;
                },
            },
        },
    },
    scales: {
        x: {
            ticks: {
                color: "#9CA3AF",
            },
            grid: {
                color: "#374151",
            },
        },
        y: {
            ticks: {
                color: "#9CA3AF",
                callback: function (value: any) {
                    return "R$ " + value;
                },
            },
            grid: {
                color: "#374151",
            },
        },
    },
};
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            Receitas vs Despesas (Mensal)
        </h3>
        <div v-if="monthlyData.length > 0" class="h-80">
            <Bar :data="chartData" :options="chartOptions" />
        </div>
        <div v-else class="h-80 flex items-center justify-center">
            <p class="text-gray-500 dark:text-gray-400">
                Nenhuma transação encontrada no período
            </p>
        </div>
    </div>
</template>
