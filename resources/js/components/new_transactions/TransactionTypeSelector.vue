<script setup lang="ts">
const modelValue = defineModel<"receita" | "despesa">({ required: true });
</script>

<template>
    <div>
        <label
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
        >
            Tipo
        </label>
        <div class="grid grid-cols-2 gap-3">
            <button
                type="button"
                @click="modelValue = 'receita'"
                :class="[
                    'px-4 py-3 rounded-xl font-medium transition-all border-2',
                    modelValue === 'receita'
                        ? 'bg-green-50 dark:bg-green-900/20 border-green-500 text-green-700 dark:text-green-400'
                        : 'border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400',
                ]"
            >
                Receita
            </button>
            <button
                type="button"
                @click="modelValue = 'despesa'"
                :class="[
                    'px-4 py-3 rounded-xl font-medium transition-all border-2',
                    modelValue === 'despesa'
                        ? 'bg-red-50 dark:bg-red-900/20 border-red-500 text-red-700 dark:text-red-400'
                        : 'border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400',
                ]"
            >
                Despesa
            </button>
        </div>
    </div>
</template>