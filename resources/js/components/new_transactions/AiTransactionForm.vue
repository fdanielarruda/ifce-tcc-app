<script setup lang="ts">
import { ref } from "vue";
import { SparklesIcon } from "@heroicons/vue/24/outline";
import { useTransactionStore } from "@/stores/useTransactionStore";

const emit = defineEmits<{
    success: [];
}>();

const transactionStore = useTransactionStore();
const description = ref("");

const submit = async () => {
    try {
        await transactionStore.createAiTransaction(
            { description: description.value },
            {
                onSuccess: () => {
                    description.value = "";
                    emit("success");
                },
            }
        );
    } catch (error) {
        console.error("Erro ao criar transação:", error);
    }
};
</script>

<template>
    <div class="space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
            <div class="mb-4">
                <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                >
                    Descreva sua transação
                </label>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                    Ex: "Gastei 45 reais no supermercado hoje" ou "Recebi meu salário de
                    2000 reais"
                </p>
                <textarea
                    v-model="description"
                    rows="4"
                    placeholder="Digite aqui sua transação..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                    :disabled="transactionStore.isLoading.value"
                ></textarea>
                <p
                    v-if="transactionStore.errors.value.description"
                    class="mt-2 text-sm text-red-600 dark:text-red-400"
                >
                    {{ transactionStore.errors.value.description }}
                </p>
            </div>

            <button
                @click="submit"
                :disabled="!description || transactionStore.isLoading.value"
                class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 dark:disabled:bg-gray-600 text-white font-semibold py-4 rounded-xl transition-colors disabled:cursor-not-allowed flex items-center justify-center gap-2"
            >
                <SparklesIcon v-if="!transactionStore.isLoading.value" class="w-5 h-5" />
                <span v-if="transactionStore.isLoading.value">Processando...</span>
                <span v-else>Criar com IA</span>
            </button>
        </div>
    </div>
</template>
