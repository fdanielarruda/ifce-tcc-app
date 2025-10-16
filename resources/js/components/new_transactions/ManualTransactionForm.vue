<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import TransactionTypeSelector from "./TransactionTypeSelector.vue";
import AmountInput from "./AmountInput.vue";

interface Props {
    categories: Array<{
        id: number;
        title: string;
    }>;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    success: [];
}>();

const form = useForm({
    title: "",
    amount: "",
    category_id: "",
    type: "despesa" as "receita" | "despesa",
    date: new Date().toISOString().split("T")[0],
    time: new Date().toTimeString().slice(0, 5),
});

const submit = () => {
    form.post(route("transactions.store"), {
        onSuccess: () => {
            form.reset();
            emit("success");
        },
    });
};
</script>

<template>
    <div class="space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
            <form @submit.prevent="submit" class="space-y-4">
                <TransactionTypeSelector v-model="form.type" />

                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        Descrição
                    </label>
                    <input
                        v-model="form.title"
                        type="text"
                        placeholder="Ex: Compras no supermercado"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <p
                        v-if="form.errors.title"
                        class="mt-2 text-sm text-red-600 dark:text-red-400"
                    >
                        {{ form.errors.title }}
                    </p>
                </div>

                <AmountInput v-model="form.amount" :error="form.errors.amount" />

                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        Categoria
                    </label>
                    <select
                        v-model="form.category_id"
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
                        v-if="form.errors.category_id"
                        class="mt-2 text-sm text-red-600 dark:text-red-400"
                    >
                        {{ form.errors.category_id }}
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
                            v-model="form.date"
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
                            v-model="form.time"
                            type="time"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 dark:disabled:bg-gray-600 text-white font-semibold py-4 rounded-xl transition-colors disabled:cursor-not-allowed"
                >
                    <span v-if="form.processing">Salvando...</span>
                    <span v-else>Salvar Transação</span>
                </button>
            </form>
        </div>
    </div>
</template>
