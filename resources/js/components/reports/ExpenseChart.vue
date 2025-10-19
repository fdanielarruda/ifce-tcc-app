<script setup lang="ts">
import { computed } from "vue";
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from "chart.js";
import { Doughnut } from "vue-chartjs";

ChartJS.register(ArcElement, Tooltip, Legend);

interface CategoryData {
    category: string;
    icon?: string;
    type: string;
    total: number;
}

interface Props {
    categoryData: CategoryData[];
}

const props = defineProps<Props>();

const chartData = computed(() => {
    const despesas = props.categoryData.filter((item) => item.type === "despesa");

    return {
        labels: despesas.map((item) => item.category),
        datasets: [
            {
                data: despesas.map((item) => item.total),
                backgroundColor: [
                    "#EF4444",
                    "#F59E0B",
                    "#10B981",
                    "#3B82F6",
                    "#8B5CF6",
                    "#EC4899",
                    "#F97316",
                    "#14B8A6",
                    "#999999",
                ],
            },
        ],
    };
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: "bottom" as const,
            labels: {
                color: "#9CA3AF",
                padding: 15,
            },
        },
        tooltip: {
            callbacks: {
                label: function (context: any) {
                    return `${context.label}: R$ ${context.parsed.toFixed(2)}`;
                },
            },
        },
    },
};

const hasExpenses = computed(
    () => props.categoryData.filter((item) => item.type === "despesa").length > 0
);
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            Despesas por Categoria
        </h3>
        <div v-if="hasExpenses" class="h-80">
            <Doughnut :data="chartData" :options="chartOptions" />
        </div>
        <div v-else class="h-80 flex items-center justify-center">
            <p class="text-gray-500 dark:text-gray-400">
                Nenhuma despesa encontrada no período
            </p>
        </div>
    </div>
</template>
