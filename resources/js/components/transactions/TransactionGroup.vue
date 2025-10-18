<script setup lang="ts">
import TransactionCard from "./TransactionCard.vue";

interface Transaction {
    id: number;
    description: string;
    category: string;
    category_id: number;
    time: string;
    amount: number;
    type: "receita" | "despesa";
    icon: string;
}

interface Props {
    title: string;
    transactions: Transaction[];
    categories?: Array<{
        id: number;
        title: string;
    }>;
}

defineProps<Props>();
</script>

<template>
    <div v-if="transactions.length > 0" class="mb-6">
        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
            {{ title }}
        </h3>
        <div class="space-y-3">
            <TransactionCard
                v-for="transaction in transactions"
                :key="transaction.id"
                :transaction="transaction"
                :categories="categories"
            />
        </div>
    </div>
</template>
