<script setup lang="ts">
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import HeaderCell from "@/components/commons/HeaderCell.vue";
import ReportFilters from "@/components/reports/ReportFilters.vue";
import SummaryCards from "@/components/reports/SummaryCards.vue";
import ExpenseChart from "@/components/reports/ExpenseChart.vue";
import MonthlyChart from "@/components/reports/MonthlyChart.vue";
import CategoryDetails from "@/components/reports/CategoryDetails.vue";
import { useReportStore } from "@/stores/useReportStore";

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

const { isLoading, applyFilters } = useReportStore();

const handleApplyFilters = async (filters: { start_date: string; end_date: string }) => {
    await applyFilters(filters);
};
</script>

<template>
    <Head title="Relatórios" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
            <HeaderCell title="Relatórios Financeiros" />

            <div class="p-4 space-y-6">
                <ReportFilters
                    :initial-start-date="filters.start_date"
                    :initial-end-date="filters.end_date"
                    :is-loading="isLoading"
                    @apply="handleApplyFilters"
                />

                <SummaryCards :totals="totals" />

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <ExpenseChart :category-data="categoryData" />
                    <MonthlyChart :monthly-data="monthlyData" />
                </div>

                <CategoryDetails :category-data="categoryData" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
