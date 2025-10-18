<script setup lang="ts">
interface Props {
    description: string;
    amount: string;
    category_id: number | null;
    type: "receita" | "despesa";
    date: string;
    time: string;
    categories: Array<{
        id: number;
        title: string;
    }>;
    errors: Record<string, string>;
    showTypeSelector?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    showTypeSelector: false,
});

const emit = defineEmits<{
    "update:description": [value: string];
    "update:amount": [value: string];
    "update:category_id": [value: number | null];
    "update:type": [value: "receita" | "despesa"];
    "update:date": [value: string];
    "update:time": [value: string];
}>();

const formatAmount = (event: Event) => {
    const input = event.target as HTMLInputElement;
    let value = input.value.replace(/[^\d,]/g, "");

    const parts = value.split(",");
    if (parts.length > 2) {
        value = parts[0] + "," + parts.slice(1).join("");
    }

    emit("update:amount", value);
};
</script>

<template>
    <div class="space-y-4">
        <div v-if="showTypeSelector" class="flex gap-2">
            <button
                type="button"
                @click="emit('update:type', 'despesa')"
                :class="[
                    'flex-1 py-3 px-4 rounded-lg font-semibold transition-all',
                    type === 'despesa'
                        ? 'bg-red-500 text-white shadow-sm'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
                ]"
            >
                Despesa
            </button>
            <button
                type="button"
                @click="emit('update:type', 'receita')"
                :class="[
                    'flex-1 py-3 px-4 rounded-lg font-semibold transition-all',
                    type === 'receita'
                        ? 'bg-green-500 text-white shadow-sm'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
                ]"
            >
                Receita
            </button>
        </div>

        <div>
            <label
                for="description"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
            >
                Descrição
            </label>
            <input
                id="description"
                type="text"
                :value="description"
                @input="emit('update:description', ($event.target as HTMLInputElement).value)"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Ex: Almoço no restaurante"
            />
            <p
                v-if="errors.description"
                class="mt-1 text-sm text-red-600 dark:text-red-400"
            >
                {{ errors.description }}
            </p>
        </div>

        <div>
            <label
                for="amount"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
            >
                Valor
            </label>
            <div class="relative">
                <span
                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium"
                >
                    R$
                </span>
                <input
                    id="amount"
                    type="text"
                    :value="amount"
                    @input="formatAmount"
                    class="w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="0,00"
                />
            </div>
            <p v-if="errors.amount" class="mt-1 text-sm text-red-600 dark:text-red-400">
                {{ errors.amount }}
            </p>
        </div>

        <div>
            <label
                for="category"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
            >
                Categoria
            </label>
            <select
                id="category"
                :value="category_id"
                @change="emit('update:category_id', Number(($event.target as HTMLSelectElement).value))"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
                <option value="">Selecione uma categoria</option>
                <option
                    v-for="category in categories"
                    :key="category.id"
                    :value="category.id"
                >
                    {{ category.title }}
                </option>
            </select>
            <p
                v-if="errors.category_id"
                class="mt-1 text-sm text-red-600 dark:text-red-400"
            >
                {{ errors.category_id }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label
                    for="date"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                >
                    Data
                </label>
                <input
                    id="date"
                    type="date"
                    :value="date"
                    @input="emit('update:date', ($event.target as HTMLInputElement).value)"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <p v-if="errors.date" class="mt-1 text-sm text-red-600 dark:text-red-400">
                    {{ errors.date }}
                </p>
            </div>

            <div>
                <label
                    for="time"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                >
                    Hora
                </label>
                <input
                    id="time"
                    type="time"
                    :value="time"
                    @input="emit('update:time', ($event.target as HTMLInputElement).value)"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <p v-if="errors.time" class="mt-1 text-sm text-red-600 dark:text-red-400">
                    {{ errors.time }}
                </p>
            </div>
        </div>
    </div>
</template>
