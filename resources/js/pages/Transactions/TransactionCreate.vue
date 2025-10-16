<script setup lang="ts">
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { SparklesIcon, PencilSquareIcon, XMarkIcon } from "@heroicons/vue/24/outline";

interface Props {
    categories: Array<{
        id: number;
        title: string;
    }>;
}

const props = defineProps<Props>();

const mode = ref<"ai" | "manual">("ai");
const isProcessing = ref(false);

const aiForm = useForm({
    description: "",
});

const manualForm = useForm({
    title: "",
    amount: "",
    category_id: "",
    type: "despesa" as "receita" | "despesa",
    date: new Date().toISOString().split("T")[0],
    time: new Date().toTimeString().slice(0, 5),
});

const submitAiTransaction = () => {
    isProcessing.value = true;
    aiForm.post(route("transactions.store.ai"), {
        onSuccess: () => {
            aiForm.reset();
            router.visit(route("transactions.index"));
        },
        onFinish: () => {
            isProcessing.value = false;
        },
    });
};

const submitManualTransaction = () => {
    manualForm.post(route("transactions.store"), {
        onSuccess: () => {
            manualForm.reset();
            router.visit(route("transactions.index"));
        },
    });
};

const formatAmountInput = (event: Event) => {
    const input = event.target as HTMLInputElement;
    let value = input.value.replace(/[^\d,]/g, "");

    // Permite apenas uma vírgula
    const parts = value.split(",");
    if (parts.length > 2) {
        value = parts[0] + "," + parts.slice(1).join("");
    }

    input.value = value;
    manualForm.amount = value;
};
</script>

<template>
    <Head title="Nova Transação" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900 pb-20">
            <div class="bg-white dark:bg-gray-800 shadow-sm">
                <div class="p-4 flex items-center justify-center">
                    <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        Nova Transação
                    </h1>
                </div>
            </div>

            <div class="p-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-2 mb-6">
                    <div class="grid grid-cols-2 gap-2">
                        <button
                            @click="mode = 'ai'"
                            :class="[
                                'flex items-center justify-center gap-2 px-4 py-3 rounded-xl font-medium transition-all',
                                mode === 'ai'
                                    ? 'bg-blue-600 text-white shadow-md'
                                    : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700',
                            ]"
                        >
                            <SparklesIcon class="w-5 h-5" />
                            <span>Com IA</span>
                        </button>
                        <button
                            @click="mode = 'manual'"
                            :class="[
                                'flex items-center justify-center gap-2 px-4 py-3 rounded-xl font-medium transition-all',
                                mode === 'manual'
                                    ? 'bg-blue-600 text-white shadow-md'
                                    : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700',
                            ]"
                        >
                            <PencilSquareIcon class="w-5 h-5" />
                            <span>Manual</span>
                        </button>
                    </div>
                </div>

                <div v-if="mode === 'ai'" class="space-y-4">
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
                                v-model="aiForm.description"
                                rows="4"
                                placeholder="Digite aqui sua transação..."
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                :disabled="isProcessing"
                            ></textarea>
                            <p
                                v-if="aiForm.errors.description"
                                class="mt-2 text-sm text-red-600 dark:text-red-400"
                            >
                                {{ aiForm.errors.description }}
                            </p>
                        </div>

                        <button
                            @click="submitAiTransaction"
                            :disabled="!aiForm.description || isProcessing"
                            class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 dark:disabled:bg-gray-600 text-white font-semibold py-4 rounded-xl transition-colors disabled:cursor-not-allowed flex items-center justify-center gap-2"
                        >
                            <SparklesIcon v-if="!isProcessing" class="w-5 h-5" />
                            <span v-if="isProcessing">Processando...</span>
                            <span v-else>Criar com IA</span>
                        </button>
                    </div>
                </div>

                <div v-if="mode === 'manual'" class="space-y-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
                        <form @submit.prevent="submitManualTransaction" class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    Tipo
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <button
                                        type="button"
                                        @click="manualForm.type = 'receita'"
                                        :class="[
                                            'px-4 py-3 rounded-xl font-medium transition-all border-2',
                                            manualForm.type === 'receita'
                                                ? 'bg-green-50 dark:bg-green-900/20 border-green-500 text-green-700 dark:text-green-400'
                                                : 'border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400',
                                        ]"
                                    >
                                        Receita
                                    </button>
                                    <button
                                        type="button"
                                        @click="manualForm.type = 'despesa'"
                                        :class="[
                                            'px-4 py-3 rounded-xl font-medium transition-all border-2',
                                            manualForm.type === 'despesa'
                                                ? 'bg-red-50 dark:bg-red-900/20 border-red-500 text-red-700 dark:text-red-400'
                                                : 'border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400',
                                        ]"
                                    >
                                        Despesa
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    Descrição
                                </label>
                                <input
                                    v-model="manualForm.title"
                                    type="text"
                                    placeholder="Ex: Compras no supermercado"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                                <p
                                    v-if="manualForm.errors.title"
                                    class="mt-2 text-sm text-red-600 dark:text-red-400"
                                >
                                    {{ manualForm.errors.title }}
                                </p>
                            </div>

                            <div>
                                <label
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
                                        :value="manualForm.amount"
                                        @input="formatAmountInput"
                                        type="text"
                                        placeholder="0,00"
                                        class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                </div>
                                <p
                                    v-if="manualForm.errors.amount"
                                    class="mt-2 text-sm text-red-600 dark:text-red-400"
                                >
                                    {{ manualForm.errors.amount }}
                                </p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    Categoria
                                </label>
                                <select
                                    v-model="manualForm.category_id"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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
                                    v-if="manualForm.errors.category_id"
                                    class="mt-2 text-sm text-red-600 dark:text-red-400"
                                >
                                    {{ manualForm.errors.category_id }}
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    >
                                        Data
                                    </label>
                                    <input
                                        v-model="manualForm.date"
                                        type="date"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    >
                                        Hora
                                    </label>
                                    <input
                                        v-model="manualForm.time"
                                        type="time"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                </div>
                            </div>

                            <button
                                type="submit"
                                :disabled="manualForm.processing"
                                class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 dark:disabled:bg-gray-600 text-white font-semibold py-4 rounded-xl transition-colors disabled:cursor-not-allowed"
                            >
                                <span v-if="manualForm.processing">Salvando...</span>
                                <span v-else>Salvar Transação</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
