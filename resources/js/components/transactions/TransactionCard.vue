<script setup lang="ts">
import { ShoppingCartIcon, BanknotesIcon, TruckIcon } from "@heroicons/vue/24/outline";

interface Props {
    transaction: {
        id: number;
        title: string;
        category: string;
        time: string;
        amount: number;
        type: "receita" | "despesa";
        icon: string;
    };
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
</script>

<template>
    <div
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
                <p class="font-medium text-gray-800 dark:text-gray-200">
                    {{ transaction.title }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ transaction.category }} • {{ transaction.time }}
                </p>
            </div>
        </div>
        <span
            :class="[
                'font-semibold text-base',
                transaction.type === 'receita'
                    ? 'text-green-600 dark:text-green-400'
                    : 'text-red-600 dark:text-red-400',
            ]"
        >
            {{ formatCurrency(transaction.amount) }}
        </span>
    </div>
</template>
