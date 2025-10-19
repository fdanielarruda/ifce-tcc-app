<script setup lang="ts">
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import HeaderCell from "@/components/commons/HeaderCell.vue";
import {
    Chart as ChartJS,
    ArcElement,
    Tooltip,
    Legend,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
} from "chart.js";
import { Doughnut, Bar } from "vue-chartjs";
import { getIconComponent } from "@/utils/iconHelper";

ChartJS.register(
    ArcElement,
    Tooltip,
    Legend,
    CategoryScale,
    LinearScale,
    BarElement,
    Title
);

interface CategoryData {
    category: string;
    icon?: string;
    type: string;
    total: number;
}

interface MonthlyData {
    month: string;
    receita: number;
    despesa: number;
}

interface Totals {
    receitas: number;
    despesas: number;
    saldo: number;
}

interface Filters {
    start_date: string;
    end_date: string;
}

interface Props {
    categoryData: CategoryData[];
    monthlyData: MonthlyData[];
    totals: Totals;
    filters: Filters;
}

const props = defineProps<Props>();

const startDate = ref(props.filters.start_date);
const endDate = ref(props.filters.end_date);

const doughnutChartData = computed(() => {
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
                ],
            },
        ],
    };
});

const doughnutChartOptions = {
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

const barChartData = computed(() => ({
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

const barChartOptions = {
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

const applyFilters = () => {
    router.get(
        route("reports.index"),
        {
            start_date: startDate.value,
            end_date: endDate.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat("pt-BR", {
        style: "currency",
        currency: "BRL",
    }).format(value);
};
</script>

<template>
    <Head title="Relatórios" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
            <HeaderCell title="Relatórios Financeiros" />

            <div class="p-4 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Filtros
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Data Início
                            </label>
                            <input
                                v-model="startDate"
                                type="date"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Data Fim
                            </label>
                            <input
                                v-model="endDate"
                                type="date"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <div class="flex items-end">
                            <button
                                @click="applyFilters"
                                class="w-full px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
                            >
                                Aplicar Filtros
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div
                        class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-md p-6 text-white"
                    >
                        <p class="text-sm opacity-90 mb-1">Total Receitas</p>
                        <p class="text-3xl font-bold">
                            {{ formatCurrency(totals.receitas) }}
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-md p-6 text-white"
                    >
                        <p class="text-sm opacity-90 mb-1">Total Despesas</p>
                        <p class="text-3xl font-bold">
                            {{ formatCurrency(totals.despesas) }}
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-md p-6 text-white"
                    >
                        <p class="text-sm opacity-90 mb-1">Saldo</p>
                        <p class="text-3xl font-bold">
                            {{ formatCurrency(totals.saldo) }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-white mb-4"
                        >
                            Despesas por Categoria
                        </h3>
                        <div v-if="categoryData.length > 0" class="h-80">
                            <Doughnut
                                :data="doughnutChartData"
                                :options="doughnutChartOptions"
                            />
                        </div>
                        <div v-else class="h-80 flex items-center justify-center">
                            <p class="text-gray-500 dark:text-gray-400">
                                Nenhuma despesa encontrada no período
                            </p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-white mb-4"
                        >
                            Receitas vs Despesas (Mensal)
                        </h3>
                        <div v-if="monthlyData.length > 0" class="h-80">
                            <Bar :data="barChartData" :options="barChartOptions" />
                        </div>
                        <div v-else class="h-80 flex items-center justify-center">
                            <p class="text-gray-500 dark:text-gray-400">
                                Nenhuma transação encontrada no período
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Detalhamento por Categoria
                    </h3>
                    <div class="space-y-3">
                        <div
                            v-for="item in categoryData"
                            :key="item.category + item.type"
                            class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0"
                                >
                                    <component
                                        :is="getIconComponent(item.icon)"
                                        class="w-6 h-6"
                                    />
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ item.category }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{
                                            item.type === "receita"
                                                ? "Receita"
                                                : "Despesa"
                                        }}
                                    </p>
                                </div>
                            </div>
                            <p
                                :class="[
                                    'font-semibold',
                                    item.type === 'receita'
                                        ? 'text-green-600'
                                        : 'text-red-600',
                                ]"
                            >
                                {{ formatCurrency(item.total) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
