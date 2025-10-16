<script setup lang="ts">
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import {
    ShoppingCartIcon,
    BanknotesIcon,
    TruckIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
} from "@heroicons/vue/24/outline";

interface Transaction {
    id: number;
    title: string;
    category: string;
    time: string;
    amount: number;
    type: "income" | "expense";
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

const formatCurrency = (value: number) => {
    const prefix = value >= 0 ? "+" : "-";
    const absValue = Math.abs(value);
    return `${prefix}R$ ${absValue.toFixed(2).replace(".", ",")}`;
};

const getIconComponent = (iconType: string) => {
    const icons: Record<string, any> = {
        shopping: ShoppingCartIcon,
        salary: BanknotesIcon,
        fuel: TruckIcon,
    };
    return icons[iconType] || ShoppingCartIcon;
};

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
</script>

<template>
    <Head title="Transações" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
            <div class="p-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-2">
                        Saldo Total
                    </p>
                    <h2
                        class="text-4xl font-bold text-gray-800 dark:text-gray-200 text-center mb-6"
                    >
                        R$ {{ props.totalBalance.toFixed(2).replace(".", ",") }}
                    </h2>

                    <div class="flex justify-around">
                        <div class="text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                Receitas
                            </p>
                            <p
                                class="text-lg font-semibold text-green-600 dark:text-green-400"
                            >
                                R$ {{ props.totalIncome.toFixed(2).replace(".", ",") }}
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                Despesas
                            </p>
                            <p
                                class="text-lg font-semibold text-red-600 dark:text-red-400"
                            >
                                R$ {{ props.totalExpenses.toFixed(2).replace(".", ",") }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-4 py-3">
                <div
                    class="flex bg-white dark:bg-gray-800 items-center justify-between w-full rounded-xl shadow-sm p-1"
                >
                    <button
                        @click="navigateMonth('prev')"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                    >
                        <ChevronLeftIcon
                            class="w-5 h-5 text-gray-600 dark:text-gray-300"
                        />
                    </button>
                    <span class="text-base font-medium text-gray-700 dark:text-gray-300">
                        {{ props.currentMonth }}
                    </span>
                    <button
                        @click="navigateMonth('next')"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                    >
                        <ChevronRightIcon
                            class="w-5 h-5 text-gray-600 dark:text-gray-300"
                        />
                    </button>
                </div>
            </div>

            <div class="px-4 pb-4">
                <div v-if="props.todayTransactions.length > 0" class="mb-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
                        Hoje
                    </h3>
                    <div class="space-y-3">
                        <div
                            v-for="transaction in props.todayTransactions"
                            :key="transaction.id"
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 flex items-center justify-between"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center"
                                >
                                    <component
                                        :is="getIconComponent(transaction.icon)"
                                        class="w-5 h-5 text-gray-600 dark:text-gray-300"
                                    />
                                </div>
                                <div>
                                    <p
                                        class="font-medium text-gray-800 dark:text-gray-200"
                                    >
                                        {{ transaction.title }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ transaction.category }} •
                                        {{ transaction.time }}
                                    </p>
                                </div>
                            </div>
                            <span
                                :class="[
                                    'font-semibold text-base',
                                    transaction.type === 'income'
                                        ? 'text-green-600 dark:text-green-400'
                                        : 'text-red-600 dark:text-red-400',
                                ]"
                            >
                                {{ formatCurrency(transaction.amount) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div v-if="props.yesterdayTransactions.length > 0" class="mb-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
                        Ontem
                    </h3>
                    <div class="space-y-3">
                        <div
                            v-for="transaction in props.yesterdayTransactions"
                            :key="transaction.id"
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 flex items-center justify-between"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center"
                                >
                                    <component
                                        :is="getIconComponent(transaction.icon)"
                                        class="w-5 h-5 text-gray-600 dark:text-gray-300"
                                    />
                                </div>
                                <div>
                                    <p
                                        class="font-medium text-gray-800 dark:text-gray-200"
                                    >
                                        {{ transaction.title }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ transaction.category }} •
                                        {{ transaction.time }}
                                    </p>
                                </div>
                            </div>
                            <span
                                :class="[
                                    'font-semibold text-base',
                                    transaction.type === 'income'
                                        ? 'text-green-600 dark:text-green-400'
                                        : 'text-red-600 dark:text-red-400',
                                ]"
                            >
                                {{ formatCurrency(transaction.amount) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div
                    v-if="props.olderTransactions && props.olderTransactions.length > 0"
                    v-for="group in props.olderTransactions"
                    :key="group.date"
                    class="mb-6"
                >
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
                        {{ group.date }}
                    </h3>
                    <div class="space-y-3">
                        <div
                            v-for="transaction in group.transactions"
                            :key="transaction.id"
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 flex items-center justify-between"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center"
                                >
                                    <component
                                        :is="getIconComponent(transaction.icon)"
                                        class="w-5 h-5 text-gray-600 dark:text-gray-300"
                                    />
                                </div>
                                <div>
                                    <p
                                        class="font-medium text-gray-800 dark:text-gray-200"
                                    >
                                        {{ transaction.title }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ transaction.category }} •
                                        {{ transaction.time }}
                                    </p>
                                </div>
                            </div>
                            <span
                                :class="[
                                    'font-semibold text-base',
                                    transaction.type === 'income'
                                        ? 'text-green-600 dark:text-green-400'
                                        : 'text-red-600 dark:text-red-400',
                                ]"
                            >
                                {{ formatCurrency(transaction.amount) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div
                    v-if="
                        props.todayTransactions.length === 0 &&
                        props.yesterdayTransactions.length === 0 &&
                        (!props.olderTransactions || props.olderTransactions.length === 0)
                    "
                    class="text-center py-12"
                >
                    <p class="text-gray-500 dark:text-gray-400">
                        Nenhuma transação encontrada neste mês
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
