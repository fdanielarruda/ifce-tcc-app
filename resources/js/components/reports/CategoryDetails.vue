<script setup lang="ts">
import { getIconComponent } from "@/utils/iconHelper";

interface CategoryData {
    category: string;
    icon?: string;
    type: string;
    total: number;
}

interface Props {
    categoryData: CategoryData[];
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
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            Detalhamento por Categoria
        </h3>
        <div v-if="categoryData.length > 0" class="space-y-3">
            <div
                v-for="item in categoryData"
                :key="item.category + item.type"
                class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0"
                    >
                        <component :is="getIconComponent(item.icon)" class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">
                            {{ item.category }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ item.type === "receita" ? "Receita" : "Despesa" }}
                        </p>
                    </div>
                </div>
                <p
                    :class="[
                        'font-semibold',
                        item.type === 'receita' ? 'text-green-600' : 'text-red-600',
                    ]"
                >
                    {{ formatCurrency(item.total) }}
                </p>
            </div>
        </div>
        <div v-else class="flex items-center justify-center py-12">
            <p class="text-gray-500 dark:text-gray-400">
                Nenhuma categoria encontrada no período
            </p>
        </div>
    </div>
</template>
