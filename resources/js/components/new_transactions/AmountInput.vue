<script setup lang="ts">
interface Props {
    error?: string;
}

const props = defineProps<Props>();

const modelValue = defineModel<string>({ required: true });

const formatAmount = (event: Event) => {
    const input = event.target as HTMLInputElement;
    let value = input.value.replace(/[^\d,]/g, "");

    const parts = value.split(",");
    if (parts.length > 2) {
        value = parts[0] + "," + parts.slice(1).join("");
    }

    input.value = value;
    modelValue.value = value;
};
</script>

<template>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Valor
        </label>
        <div class="relative">
            <span
                class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium"
            >
                R$
            </span>
            <input
                :value="modelValue"
                @input="formatAmount"
                type="text"
                placeholder="0,00"
                class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
        </div>
        <p v-if="error" class="mt-2 text-sm text-red-600 dark:text-red-400">
            {{ error }}
        </p>
    </div>
</template>
