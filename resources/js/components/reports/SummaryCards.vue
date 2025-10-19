<script setup lang="ts">
interface Totals {
    receitas: number;
    despesas: number;
    saldo: number;
}

interface Props {
    totals: Totals;
}

defineProps<Props>();

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat("pt-BR", {
        style: "currency",
        currency: "BRL",
    }).format(value);
};
</script>

<template>
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
</template>
