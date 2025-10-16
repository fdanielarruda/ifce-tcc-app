<script setup lang="ts">
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import BalanceSummary from "@/components/transactions/BalanceSummary.vue";
import MonthNavigator from "@/components/transactions/MonthNavigator.vue";
import TransactionGroup from "@/components/transactions/TransactionGroup.vue";

interface Transaction {
    id: number;
    title: string;
    category: string;
    time: string;
    amount: number;
    type: "receita" | "despesa";
    icon: string;
}

interface Props {
    currentMonth: string;
    currentMonthValue: number;
    currentYearValue: number;
    totalBalance: number;
    totalIncome: number;
    totalExpenses: number;
    todayTransactions: Transaction[];
    yesterdayTransactions: Transaction[];
    olderTransactions?: Array<{
        date: string;
        transactions: Transaction[];
    }>;
}

const props = defineProps<Props>();

const navigateMonth = (direction: "prev" | "next") => {
    let newMonth = props.currentMonthValue;
    let newYear = props.currentYearValue;

    if (direction === "prev") {
        newMonth--;
        if (newMonth < 1) {
            newMonth = 12;
            newYear--;
        }
    } else {
        newMonth++;
        if (newMonth > 12) {
            newMonth = 1;
            newYear++;
        }
    }

    router.get(
        route("transactions.index"),
        {
            month: newMonth,
            year: newYear,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const hasTransactions =
    props.todayTransactions.length > 0 ||
    props.yesterdayTransactions.length > 0 ||
    (props.olderTransactions && props.olderTransactions.length > 0);
</script>

<template>
    <Head title="Transações" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
            <div class="p-4">
                <BalanceSummary
                    :total-balance="totalBalance"
                    :total-income="totalIncome"
                    :total-expenses="totalExpenses"
                />
            </div>

            <div class="px-4 py-3">
                <MonthNavigator
                    :current-month="currentMonth"
                    @navigate="navigateMonth"
                />
            </div>

            <div class="px-4 pb-4">
                <TransactionGroup
                    title="Hoje"
                    :transactions="todayTransactions"
                />

                <TransactionGroup
                    title="Ontem"
                    :transactions="yesterdayTransactions"
                />

                <TransactionGroup
                    v-for="group in olderTransactions"
                    :key="group.date"
                    :title="group.date"
                    :transactions="group.transactions"
                />

                <div v-if="!hasTransactions" class="text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400">
                        Nenhuma transação encontrada neste mês
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>