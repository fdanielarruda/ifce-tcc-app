<script setup lang="ts">
interface Props {
    totalBalance: number;
    totalIncome: number;
    totalExpenses: number;
}

defineProps<Props>();

const formatCurrency = (value: number) => {
    return `R$ ${value.toFixed(2).replace(".", ",")}`;
};
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
        <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-2">
            Saldo Total
        </p>
        <h2 class="text-4xl font-bold text-gray-800 dark:text-gray-200 text-center mb-6">
            {{ formatCurrency(totalBalance) }}
        </h2>

        <div class="flex justify-around">
            <div class="text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Receitas</p>
                <p class="text-lg font-semibold text-green-600 dark:text-green-400">
                    {{ formatCurrency(totalIncome) }}
                </p>
            </div>
            <div class="text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Despesas</p>
                <p class="text-lg font-semibold text-red-600 dark:text-red-400">
                    {{ formatCurrency(totalExpenses) }}
                </p>
            </div>
        </div>
    </div>
</template>
