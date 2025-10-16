<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { SparklesIcon } from "@heroicons/vue/24/outline";

const emit = defineEmits<{
    success: [];
}>();

const isProcessing = ref(false);

const form = useForm({
    description: "",
});

const submit = () => {
    isProcessing.value = true;
    form.post(route("transactions.store.ai"), {
        onSuccess: () => {
            form.reset();
            emit("success");
        },
        onFinish: () => {
            isProcessing.value = false;
        },
    });
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
                    Ex: "Gastei 45 reais no supermercado hoje" ou "Recebi meu
                    salário de 2000 reais"
                </p>
                <textarea
                    v-model="form.description"
                    rows="4"
                    placeholder="Digite aqui sua transação..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                    :disabled="isProcessing"
                ></textarea>
                <p
                    v-if="form.errors.description"
                    class="mt-2 text-sm text-red-600 dark:text-red-400"
                >
                    {{ form.errors.description }}
                </p>
            </div>

            <button
                @click="submit"
                :disabled="!form.description || isProcessing"
                class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 dark:disabled:bg-gray-600 text-white font-semibold py-4 rounded-xl transition-colors disabled:cursor-not-allowed flex items-center justify-center gap-2"
            >
                <SparklesIcon v-if="!isProcessing" class="w-5 h-5" />
                <span v-if="isProcessing">Processando...</span>
                <span v-else>Criar com IA</span>
            </button>
        </div>
    </div>
</template>